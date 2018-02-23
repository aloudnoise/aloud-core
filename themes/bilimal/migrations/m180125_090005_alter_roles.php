<?php 
use common\components\Migration;

class m180125_090005_alter_roles extends Migration
{
    public function safeUp()
    {
        $organization_ids = \bilimal\web\models\BilimalOrganization::find()
            ->select(['organization_id'])->andWhere([
                'institute_type' => \bilimal\web\models\BilimalOrganization::TYPE_OLD_BILIMAL
            ])->asArray()->column();

        $users = \common\models\relations\UserOrganization::find()->andWhere([
            'role' => 'admin'
        ])
            ->andWhere([
                'in', 'target_id', $organization_ids
            ])->all();

        foreach ($users as $user) {
            $bu = \bilimal\web\models\BilimalUser::find()->andWhere([
                'user_id' => $user->related_id
            ])->one();

            if ($bu) {

                \bilimal\web\models\old\ServerList::$_serverId = $bu->institution_id;

                $school_admin = \bilimal\web\models\old\SchoolUsers::find()->andWhere([
                    'person_id' => $bu->bilimal_user_id,
                    'role_id' => 1,
                    'is_deleted' => false
                ])->one();

                if ($school_admin) {
                    $user->role = 'admin';
                    if (!$user->save()) {
                        var_dump($user->getErrors());
                        return false;
                    }
                } else {
                    $user->role = 'pupil';
                    if (!$user->save()) {
                        var_dump($user->getErrors());
                        return false;
                    }
                }
            }

        }
    }

    public function safeDown()
    {
        echo "m180125_090005_alter_roles cannot be reverted.\n";
        return false;
    }
}
