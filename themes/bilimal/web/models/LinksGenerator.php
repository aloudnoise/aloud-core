<?php
namespace bilimal\web\models;

use bilimal\common\models\relations\UserOrganization;
use bilimal\web\models\old\CollegeInfo;
use bilimal\web\models\old\SchoolUsers;
use bilimal\web\models\old\ServerList;
use bilimal\web\models\version2\Person;
use bilimal\web\models\version2\PersonInstitutionLink;
use common\components\Model;
use common\models\Users;

class LinksGenerator extends Model
{

    public $user = null;

    /**
     * @param Person $person
     * @return mixed;
     */
    public function generateLinks(Person $person)
    {

        $bu = BilimalUser::find()
            ->andWhere([
                "bilimal_user_id" => $person->id,
                'user_type' => BilimalUser::TYPE_NEW_BILIMAL
            ])->one();

        if ($bu) {
            $this->user = \app\models\Users::find()->byPk($bu->user_id)->one();
            return true;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        $user = new Users();
        $user->fio = $person->lastname." ".$person->firstname.($person->middlename ? " ".$person->middlename : "");
        if (!$user->save()) {
            $transaction->rollBack();
            $this->addErrors($user->getErrors());
            return false;
        }

        $bu = new BilimalUser();
        $bu->bilimal_user_id = $person->id;
        $bu->user_type = BilimalUser::TYPE_NEW_BILIMAL;
        $bu->user_id = $user->id;

        if (!$bu->save()) {
            $transaction->rollBack();
            $this->addErrors($bu->getErrors());
            return false;
        }

        $institutions = PersonInstitutionLink::find()
            ->andWhere([
                'is_deleted' => false,
                'person_id' => $person->id
            ])
            ->all();

        if (!$institutions) {
            $this->addError("institutions", "NO INSTITUTIONS");
            return false;
        }

        $is_uo = false;
        foreach ($institutions as $institution) {

            /* @var \bilimal\web\models\version2\PersonInstitutionLink $institution */

            $io = BilimalOrganization::find()
                ->andWhere([
                    "institution_id" => $institution->institution_id,
                    "institute_type" => BilimalOrganization::TYPE_NEW_BILIMAL
                ])->one();

            if (!$io) {

                $organization = new Organizations();
                $organization->name = $institution->institution->caption;
                $organization->type = Organizations::TYPE_BILIMAL_SCHOOL_SYSTEM;
                if (!$organization->save()) {
                    $transaction->rollBack();
                    $this->addErrors($organization->getErrors());
                    return false;
                }

                $io = new BilimalOrganization();
                $io->institution_id = $institution->institution_id;
                $io->institute_type = BilimalOrganization::TYPE_NEW_BILIMAL;
                $io->organization_id = $organization->id;

                if (!$io->save()) {
                    $transaction->rollBack();
                    $this->addErrors($io->getErrors());
                    return false;
                }

            } else {
                $organization = new Organizations();
                $organization->id = $io->organization_id;
            }

            $roles = [
                'pupil' => 'pupil',
                'entrant' => 'pupil',
                'student' => 'pupil',
                'teacher' => 'teacher',
                //'parent' => 'bilimal_parent'
            ];

            if (isset($roles[$institution->person_type])) {

                $uo = new UserOrganization();
                $uo->organization_id = $organization->id;
                $uo->related_id = $user->id;
                $uo->target_id = $organization->id;

                $uo->role = $roles[$institution->person_type];

                if (!$uo->save()) {
                    $transaction->rollBack();
                    $this->addErrors($uo->getErrors());
                    return false;
                }
                $is_uo = true;
            }

        }

        if (!$is_uo) {
            $transaction->rollBack();
            $this->addError("uo", "NO UO");
            return false;
        }

        $transaction->commit();
        $this->user = \app\models\Users::find()->byPk($user->id)->one();
        return true;

    }

    /**
     * @param Person $person
     * @return mixed;
     */
    public function generateLinksOld($old_user, $is_profile = false, $role_id = null)
    {

        $models = [
            3 => 'bilimal\web\models\old\Pupil',
            4 => 'bilimal\web\models\old\Employeer'
        ];

        if (!$is_profile) {
            $person = $old_user->person;
            $role_id = $old_user->role_id;
            if (!isset($models[$role_id])) {
                $this->addError("role", "NO ROLE " . $role_id);
                return false;
            }
            if (!$person OR !$person->server_id) {
                $this->addError("person", "NO PERSON OR SERVER_ID");
                return false;
            }

            ServerList::$_serverId = $person->server_id;

            $profile = $models[$role_id]::find()->andWhere([
                'portal_user_id' => $old_user->id
            ])->one();

            if (!$profile) {

                $uins = [
                    3 => 'p',
                    4 => 't'
                ];

                $profile_id = explode($uins[$role_id], $person->uin);
                $profile_id = $profile_id[1];
                $profile = $models[$role_id]::find()->byPk($profile_id)->one();

                if (!$profile) {
                    $this->addError("profile", "NO PROFILE");
                    return false;
                }
            }
        } else {
            $profile = $old_user;
            $profile_id = $profile->id;
        }

        $bu = BilimalUser::find()
            ->andWhere([
                "bilimal_user_id" => $profile->id,
                'user_type' => BilimalUser::TYPE_OLD_BILIMAL,
                'institution_id' => ServerList::getServerId()
            ])->one();

        if ($bu) {
            $this->user = \app\models\Users::find()->byPk($bu->user_id)->one();
            return true;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        $user = new Users();
        $user->fio = $profile->last_name." ".$profile->first_name.($profile->middle_name ? " ".$profile->middle_name : "");
        if (!$user->save()) {
            $transaction->rollBack();
            $this->addErrors($user->getErrors());
            return false;
        }

        $bu = new BilimalUser();
        $bu->bilimal_user_id = $profile->id;
        $bu->user_type = BilimalUser::TYPE_OLD_BILIMAL;
        $bu->institution_id = ServerList::getServerId();
        $bu->user_id = $user->id;

        if (!$bu->save()) {
            $transaction->rollBack();
            $this->addErrors($bu->getErrors());
            return false;
        }

        $organization = Organizations::createOrganizationFromServer(ServerList::$_serverId, $transaction, $this);

        $roles = [
            3 => 'pupil',
            4 => 'teacher',
        ];

        if (isset($roles[$role_id])) {

            $uo = new UserOrganization();
            $uo->organization_id = $organization->id;
            $uo->related_id = $user->id;
            $uo->target_id = $organization->id;

            // CHECK IF USER IS ADMIN IN SCHOOL
            $school_admin = SchoolUsers::find()->andWhere([
                'person_id' => $profile_id,
                'role_id' => 1,
                'is_deleted' => false
            ])->one();

            if ($school_admin) {
                $uo->role = "admin";
            } else {
                $uo->role = $roles[$role_id];
            }

            if ($uo->role == 'pupil') {
                $uo->updateCustomFields();
            }

            if (!$uo->save()) {
                $transaction->rollBack();
                $this->addErrors($uo->getErrors());
                return false;
            }



            $is_uo = true;
        }


        if (!$is_uo) {
            $transaction->rollBack();
            $this->addError("uo", "NO UO");
            return false;
        }

        $transaction->commit();
        $this->user = \app\models\Users::find()->byPk($user->id)->one();
        return true;

    }

}