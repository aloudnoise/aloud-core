<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 11.01.2018
 * Time: 0:46
 */

namespace app\models\forms;


use app\components\VarDumper;
use app\models\Groups;
use app\models\Users;
use common\components\Model;
use common\helpers\Common;
use common\models\AuthCredentials;
use common\models\Organizations;
use common\models\relations\GroupUser;
use common\models\relations\UserOrganization;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UsersImportForm extends Model
{

    public $document = null;

    public $rows = [];
    public $columns = [];
    public $group = null;
    public $division = null;

    public $generated_ids = [];

    public function rules()
    {
        return [
            [['rows','columns'], 'required'],
            [['division'], 'string'],
            [['group'], 'string'],
            [['rows'], function() {
                if (empty($this->rows)) {
                    $this->addError("rows", "Должна быть хотябы одна строка");
                    return false;
                }
                return true;
            }],
            [['columns'], function() {
                $empty = true;
                if (!empty($this->columns)) {
                    foreach ($this->columns as $i => $col) {
                        if (!empty($col)) {
                            $empty = false;
                        } else {
                            unset($this->columns[$i]);
                        }
                    }
                }
                if ($empty) {
                    $this->addError("rows", "Укажите столбцы");
                    return false;
                }

                if (!in_array("login", $this->columns)) {
                    $this->addError("rows", "Должен быть указан столбец с индентификационным номером");
                    return false;
                }

                if (!in_array("fio", $this->columns)) {
                    $this->addError("rows", "Должен быть указан столбец с ФИО");
                    return false;
                }

                if (count($this->columns) !== count(array_unique($this->columns))) {
                    $this->addError("rows", "Типы столбцов не должны повторяться");
                    return false;
                };

                return true;
            }]
        ];
    }

    private $_data = null;
    public function getData()
    {

        if (empty($this->_data)) {
            $temp = tempnam(sys_get_temp_dir(), 'TMP_');
            file_put_contents($temp, file_get_contents($this->document));
            $spreadsheet = IOFactory::load($temp);
            $worksheet = $spreadsheet->getActiveSheet();
            $this->_data = $worksheet->toArray();
            unlink($temp);
        }

        return $this->_data;

    }

    public function save()
    {

        if (!$this->validate()) return false;

        $login_column = array_filter($this->columns, function($c) {
            return $c == 'login';
        });
        $login_column = $login_column ? array_keys($login_column)[0] : null;

        $email_column = array_filter($this->columns, function($c) {
            return $c == 'email';
        });
        $email_column = $email_column ? array_keys($email_column)[0] : null;

        $phone_column = array_filter($this->columns, function($c) {
            return $c == 'phone';
        });
        $phone_column = $phone_column ? array_keys($phone_column)[0] : null;

        $fio_column = array_filter($this->columns, function($c) {
            return $c == 'fio';
        });
        $fio_column = $fio_column ? array_keys($fio_column)[0] : null;

        if ($login_column === null OR $fio_column === null) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        foreach ($this->rows as $row) {

            $row = !is_array($row) ? json_decode($row, true) : $row;

            $auth = AuthCredentials::find()
                ->with(['user'])
                ->andWhere([
                    'credential' => $row[$login_column],
                    'type' => 'login'
                ])
                ->one();

            if (!$auth) {
                $user = new Users();
                $user->is_registered = 0;
                $isNew = true;
            } else {
                $user = $auth->user;
            }

            $user->fio = $row[$fio_column];
            if (!$user->save()) {
                $this->addError("user", "Ошибка сохранения пользователя");
                $transaction->rollBack();
                return false;
            }

            if ($isNew) {
                $auth = new AuthCredentials();
                $auth->type = 'login';
                $auth->credential = (string)$row[$login_column];
                $auth->user_id = $user->id;
                $auth->validation = 'NOT_REGISTERED';
                if (!$auth->save()) {
                    $this->addError("auth_credentials", "Ошибка сохранения авторизационных данных");
                    $this->addErrors($auth->getErrors());
                    return false;
                }

                if ($email_column AND !empty($row[$email_column])) {
                    $auth = new AuthCredentials();
                    $auth->type = 'email';
                    $auth->credential = (string)$row[$email_column];
                    $auth->user_id = $user->id;
                    $auth->validation = 'NOT_REGISTERED';
                    if (!$auth->save()) {
                        $this->addError("auth_credentials", "Ошибка сохранения авторизационных данных");
                        $this->addErrors($auth->getErrors());
                        return false;
                    }
                }

                if ($phone_column AND !empty($row[$phone_column])) {
                    $auth = new AuthCredentials();
                    $auth->type = 'phone';
                    $auth->credential = (string)$row[$phone_column];
                    $auth->user_id = $user->id;
                    $auth->validation = 'NOT_REGISTERED';
                    if (!$auth->save()) {
                        $this->addError("auth_credentials", "Ошибка сохранения авторизационных данных");
                        $this->addErrors($auth->getErrors());
                        return false;
                    }
                }

            }

            $user_organization = (\Yii::$container->get('common\models\relations\UserOrganization'))::find()
                ->byOrganization()
                ->andWhere([
                    'related_id' => $user->id
                ])
                ->one();

            if ($user_organization AND $user_organization->role != 'pupil') {
                $this->addError("user_organization", "WRONG ROLE");
                $transaction->rollBack();
                return false;
            }

            if (!$user_organization) {
                $user_organization = \Yii::createObject('common\models\relations\UserOrganization');
                $user_organization->related_id = $user->id;
                $user_organization->target_id = Organizations::getCurrentOrganizationId();
                $user_organization->role = 'pupil';
            }

            foreach ($this->columns as $i => $c) {

                if (in_array($c, [
                    'login',
                    'fio',
                    'email',
                    'phone'
                ])) continue;

                $user_organization->$c = $row[$i];

            }

            if ($this->division) {
                $user_organization->division = $this->division;
            }

            if ($this->group) {

                $group = Groups::find()
                    ->byOrganization()
                    ->andWhere([
                        'name' => $this->group
                    ])->one();

                if (!$group) {
                    $group = new Groups();
                    $group->name = $this->group;
                    if (!$group->save()) {
                        $this->addError("group", "Ошибка сохранения группы");
                        $transaction->rollBack();
                        return false;
                    }
                }

                $member = GroupUser::find()
                    ->byOrganization()
                    ->andWhere([
                        'target_id' => $group->id,
                        'related_id' => $user->id
                    ])
                    ->one();

                if (!$member) {
                    $member = new GroupUser();
                    $member->target_id = $group->id;
                    $member->related_id = $user->id;
                    if (!$member->save()) {
                        $this->addError("group_user", "Ошибка сохранения члена группы");
                        $transaction->rollBack();
                        return false;
                    }
                }

            }

            if (!$user_organization->save()) {
                $this->addError("user_organization", "Ошибка сохранения пользователя в организации");
                $transaction->rollBack();
                return false;
            }

            $this->generated_ids[] = $user->id;

        }

        $transaction->commit();
        return true;


    }

}