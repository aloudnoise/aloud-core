<?php 
use common\components\Migration;

class m180125_091319_add_dic_values extends Migration
{
    public function safeUp()
    {
        $this->insert('dic_values', [
            'dic' => 'education_views',
            'value' => 'session',
            'name' => 'Сессия',
            'organization_id' => null,
            'organization_type' => \bilimal\web\models\Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM
        ]);

        $this->insert('dic_values', [
            'dic' => 'education_views',
            'value' => 'practice',
            'name' => 'Пробное',
            'organization_id' => null,
            'organization_type' => \bilimal\web\models\Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM
        ]);
    }

    public function safeDown()
    {
        echo "m180125_091319_add_dic_values cannot be reverted.\n";
        return false;
    }
}
