<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 30.01.2018
 * Time: 16:02
 */

namespace bilimal\common\models\relations;


use bilimal\web\models\BilimalOrganization;
use bilimal\web\models\BilimalUser;
use bilimal\web\models\old\Group;
use bilimal\web\models\old\Pupil;
use bilimal\web\models\old\ServerList;
use bilimal\web\models\old\Specialities;
use bilimal\web\models\Organizations;

class UserOrganization extends \common\models\relations\UserOrganization
{

    public function updateCustomFields()
    {

        $organization = $this->organization;

        Organizations::$_id = $organization->id;
        Organizations::$_current_organization = $organization;

        $bu = BilimalUser::find()->andWhere([
            'user_id' => $this->related_id
        ])->one();

        if ($bu) {

            ServerList::$_serverId = $bu->institution_id;

            $profile = Pupil::find()->byPk($bu->bilimal_user_id)->one();
            $group = Group::find()->byPk($profile->group_id)->one();

            $this->group_name = $group->name;
            $this->group_course = $group->course;

            if ($organization->type == Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM) {
                $spec = Specialities::find()->byPk($group->speciality_id)->one();
                $this->speciality = $spec->name;
            }
        }

    }

}