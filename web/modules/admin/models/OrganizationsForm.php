<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 01.11.2017
 * Time: 0:17
 */

namespace app\modules\admin\models;


use common\components\Model;
use common\models\AuthCredentials;
use common\models\Organizations;
use common\models\relations\UserOrganization;
use common\models\Users;

class OrganizationsForm extends Model
{

    public $login = null;
    public $password = null;

    public $name = null;
    public $logo = null;

    public function rules()
    {
        return [
            [['login','password','name'], 'required'],
            [['logo'], 'safe'],
            [['login'], function() {
                if (AuthCredentials::find()->andWhere([
                    'credential' => $this->login
                ])->exists()) {
                    $this->addError("login","Логин уже занят");
                    return false;
                }
                return true;
            }]
        ];
    }

    public function save()
    {

        if (!$this->validate()) return false;

        $transaction = \Yii::$app->db->beginTransaction();

        $organization = new Organizations();
        $organization->name = $this->name;
        $organization->logo = $this->logo;
        $organization->type = Organizations::TYPE_GOS_SYSTEM;

        if (!$organization->save()) {
            $this->addErrors($organization->getErrors());
            $transaction->rollBack();
            return false;
        }

        $user = new Users();
        $user->fio = $this->login;

        if (!$user->save(false)) {
            $this->addError("user", "FAILED");
            $transaction->rollBack();
            return false;
        }

        $auth = new AuthCredentials();
        $auth->scenario = 'registration';
        $auth->credential = $this->login;
        $auth->type = 'login';
        $auth->validation = $this->password;
        $auth->user_id = $user->id;
        if (!$auth->save()) {
            $this->addError("auth", "FAILED");
            $transaction->rollBack();
            return false;
        }

        $userOrg = new UserOrganization();
        $userOrg->organization_id = $organization->id;
        $userOrg->related_id = $user->id;
        $userOrg->target_id = $organization->id;
        $userOrg->role = 'admin';
        if (!$userOrg->save()) {
            $this->addErrors($userOrg->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;

    }

}