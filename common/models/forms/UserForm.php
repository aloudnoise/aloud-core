<?php
namespace common\models\forms;

use common\components\Model;
use common\components\PhoneNumberValidator;
use common\models\AuthCredentials;
use common\models\Organizations;
use common\models\Users;
use yii\helpers\VarDumper;

class UserForm extends Model
{

    public $id = null;

    public $existed = 0;

    public $login = null;
    public $email = null;
    public $phone = null;

    public $fio = null;
    public $role = null;
    public $tagsString = null;

    public $custom = [];

    public $password = null;

    public function rules()
    {
        return [
            [['login'], 'required'],
            [['login','email','phone'], function($attribute) {
                $credential = AuthCredentials::find()->byPk($this->$attribute);
                if ($this->id) {
                    $user_org = $this->getUserOrg();
                    $credential->andWhere([
                        '!=', 'user_id', $user_org->related_id
                    ]);
                }
                if ($credential->exists()) {
                    $this->addError($attribute, \Yii::t("main","Занято"));
                    return false;
                }
                return true;
            }, 'when' => function($model) {
                return !$model->existed;
            }],
            [['email'], 'email'],
            [['custom'], 'safe'],
            [['phone'], PhoneNumberValidator::className()],
            [['fio', 'role'], 'string'],
            [['password'], 'safe']
        ];
    }

    public function getUserOrg($id = null)
    {
        $id = $id ?: $this->id;
        $user_org = (\Yii::$container->get('common\models\relations\UserOrganization'))::find()
            ->byOrganization()
            ->byPk($id)
            ->one();
        return $user_org;
    }

    public function loadData($user_org_id) {

        $user_org = $this->getUserOrg($user_org_id);

        $this->id = $user_org->id;
        $this->fio = $user_org->user->fio;
        $this->role = $user_org->role;
        $this->email = $user_org->user->credentials['email']->credential;
        $this->login = $user_org->user->credentials['login']->credential;
        $this->phone = $user_org->user->credentials['phone']->credential;

        if (is_array($user_org->getCustomAttributes())) {
            foreach ($user_org->getCustomAttributes() as $attr) {
                $this->custom[$attr] = $user_org->$attr;
            }
        }
    }

    public function save()
    {

        if (!$this->validate()) return false;

        $transaction = \Yii::$app->db->beginTransaction();
        $user_org = null;
        if (!$this->existed) {

            if ($this->id) {
                $user_org = $this->getUserOrg();
            }

            if (!$user_org) {
                $user = new Users();
                $user->is_registered = 0;
            } else {
                $user = $user_org->user;
            }

            $user->fio = $this->fio;

            if (!$user->save()) {
                $this->addErrors($user->getErrors());
                $transaction->rollBack();
                return false;
            }

            $credentials = [
                'login',
                'email',
                'phone'
            ];

            foreach ($credentials as $credential) {

                if (!$user_org) {
                    if (!empty($this->$credential)) {
                        $cr = new AuthCredentials();
                        $cr->scenario = 'registration';
                        $cr->credential = $this->$credential;
                        $cr->type = $credential;
                        $cr->user_id = $user->id;
                        $cr->validation = "NOT_REGISTERED";
                        if (!$cr->save()) {
                            $this->addErrors($cr->getErrors());
                            $transaction->rollBack();
                            return false;
                        }
                    }
                } else {
                    if (!empty($this->$credential)) {
                        $cr = $user->credentials[$credential];
                        if (!$cr) {
                            $cr = new AuthCredentials();
                            $cr->type = $credential;
                            $cr->user_id = $user->id;
                            $cr->validation = $user->credentials[key($user->credentials)]->validation;
                        }
                        $cr->credential = $this->$credential;

                        if ($this->id AND $this->password) {
                            $cr->validation = \Yii::$app->security->generatePasswordHash($this->password);
                        }

                        if (!$cr->save()) {
                            $this->addErrors($cr->getErrors());
                            $transaction->rollBack();
                            return false;
                        }
                    } else {

                        AuthCredentials::deleteAll([
                            'type' => $credential,
                            'user_id' => $user->id
                        ]);
                    }

                }
            }

        } else {

            $auth = AuthCredentials::find()->andWhere([
                'credential' => $this->login,
                'type' => 'login'
            ])->one();

            if (!$auth) {
                $this->addError("login", \Yii::t("main","Пользователь не найден"));
                $transaction->rollBack();
                return false;
            }

            $user = $auth->user;

            if ((\Yii::$container->get('common\models\relations\UserOrganization'))::find()->andWhere([
                'related_id' => $user->id,
                'target_id' => Organizations::getCurrentOrganizationId()
            ])->exists()) {
                $this->addError("login", \Yii::t("main","Пользователь уже прикреплен к данной организации"));
                $transaction->rollBack();
                return false;
            }

        }

        if (!$user_org) {
            $userOrganization = \Yii::createObject('common\models\relations\UserOrganization');
            $userOrganization->related_id = $user->id;
            $userOrganization->target_id = Organizations::getCurrentOrganizationId();
        } else {
            $userOrganization = $user_org;
        }

        $userOrganization->role = $this->role;
        $userOrganization->attributes = $this->custom;
        if (!$userOrganization->save()) {
            $this->addErrors($userOrganization->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;

    }

    public function attributeLabels()
    {
        return [
            'phone' => \Yii::t("main",'Мобильный телефон'),
            'email' => \Yii::t("main",'Email'),
            'login' => \Yii::t("main",'Логин'),
            'fio' => \Yii::t("main","ФИО"),
            'role' => \Yii::t("main","Роль"),
            'tagsString' => \Yii::t("main","Ключевые слова")
        ];
    }

}