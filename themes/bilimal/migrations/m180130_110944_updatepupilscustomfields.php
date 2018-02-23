<?php 
use common\components\Migration;

class m180130_110944_updatepupilscustomfields extends Migration
{
    public function safeUp()
    {

        $organizations = \bilimal\web\models\Organizations::find()->all();

        if ($organizations) {
            foreach ($organizations as $org) {

                \bilimal\web\models\Organizations::setCurrentOrganization($org);

                $pupils = \bilimal\common\models\relations\UserOrganization::find()->byOrganization()->andWhere([
                    'role' => 'pupil'
                ])->all();

                foreach($pupils as $pupil) {
                    $pupil->updateCustomFields();
                    $pupil->save();
                }

            }
        }

    }

    public function safeDown()
    {
        echo "m180130_110944_updatepupilscustomfields cannot be reverted.\n";
        return false;
    }
}
