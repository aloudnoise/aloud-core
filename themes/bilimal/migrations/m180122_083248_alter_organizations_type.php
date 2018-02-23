<?php 
use common\components\Migration;

class m180122_083248_alter_organizations_type extends Migration
{
    public function safeUp()
    {
        $organizations = \bilimal\web\models\BilimalOrganization::find()
            ->andWhere([
                'institute_type' => \bilimal\web\models\BilimalOrganization::TYPE_OLD_BILIMAL
            ])->all();

        $types = [
            1 => \bilimal\web\models\Organizations::TYPE_BILIMAL_SCHOOL_SYSTEM,
            2 => \bilimal\web\models\Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM,
            5 => \bilimal\web\models\Organizations::TYPE_BILIMAL_GOROO_SYSTEM,
            6 => \bilimal\web\models\Organizations::TYPE_BILIMAL_UO_SYSTEM
        ];

        foreach ($organizations as $organization) {

            $org = \bilimal\web\models\Organizations::find()->byPk($organization->organization_id)->one();

            if ($org) {

                $server = \bilimal\web\models\old\ServerList::find()->byPk($organization->institution_id)->one();

                if ($server) {

                    $org->type = $types[$server->type] ?: \bilimal\web\models\Organizations::TYPE_BILIMAL_OTHER_SYSTEM;
                    if (!$org->save(false)) {
                        var_dump($org->getErrors());
                        return false;
                    }

                }
            }

        }
    }

    public function safeDown()
    {
        echo "m180122_083248_alter_organizations_type cannot be reverted.\n";
        return false;
    }
}
