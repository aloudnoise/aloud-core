<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 28.04.2017
 * Time: 17:24
 */

namespace app\models\forms;


use app\models\User;
use app\models\Users;
use app\traits\BackboneRequestTrait;
use common\components\Model;
use common\components\PhoneNumberValidator;
use common\models\AuthCredentials;

class RegistrationForm extends Model
{
    use BackboneRequestTrait;

    public $login = null;
    public $email = null;
    public $phone = null;
    public $fio = null;
    public $password = null;

    public function rules()
    {
        return [
            [['login', 'fio', 'password'], 'required'],
            [['login'], function($attribute) {
                $user = Users::find()->joinWith(['credentials'])->andWhere([
                    "auth_credentials.credential" => $this->$attribute,
                ])->one();

                if ($user) {
                    if ($user->is_registered) {
                        $this->addError($attribute, \Yii::t("main", "Пользователь с таким логином уже существует"));
                        return false;
                    }
                    if (!$user->is_registered) {
                        return true;
                    }
                }
                $this->addError($attribute, \Yii::t("main", "Пользователя с таким логином не найдено"));
                return false;
            }],
            [['email'], 'email'],
            [['phone'], PhoneNumberValidator::className()],
            [['email', 'phone'], function($attribute) {
                if (Users::find()->joinWith(['credentials'])->andWhere([
                    "auth_credentials.credential" => $this->$attribute,
                    "users.is_registered" => 1
                ])->exists()) {
                    $this->addError($attribute, \Yii::t("main","Пользователь с таким $attribute уже существует"));
                    return false;
                }
                return true;
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fio' => \Yii::t("main","ФИО"),
            'password' => \Yii::t("main","Пароль"),
            'phone' => \Yii::t("main","Телефон")
        ];
    }

    public function save()
    {

        if (!$this->validate()) return false;

        $transaction = \Yii::$app->db->beginTransaction();

        $user = Users::find()->joinWith(['credentials'])->andWhere([
            'auth_credentials.credential' => $this->login,
            'is_registered' => 0
        ])->one();

        if (!$user) {
            return false;
        }

        $user->fio = $this->fio;
        $user->is_registered = 1;

        if (!$user->save()) {
            $this->addErrors($user->getErrors());
            $transaction->rollBack();
            return false;
        }

        $credentials = [
            'email',
            'phone'
        ];

        AuthCredentials::deleteAll("user_id = :id AND type != 'login'", [
            ':id' => $user->id
        ]);

        foreach ($credentials as $credential) {

            if (!empty($this->$credential)) {
                $cr = new AuthCredentials();
                $cr->scenario = 'registration';
                $cr->credential = $this->$credential;
                $cr->type = $credential;
                $cr->user_id = $user->id;
                $cr->validation = $this->password;
                if (!$cr->save()) {
                    $this->addErrors($cr->getErrors());
                    $transaction->rollBack();
                    return false;
                }
            }
        }

        $login = AuthCredentials::find()->andWhere([
            'user_id' => $user->id,
            'type' => 'login'
        ])->one();
        if ($login) {
            $login->validation = \Yii::$app->security->generatePasswordHash($this->password);
            $login->save();
        }

        $transaction->commit();
        return true;

    }
}