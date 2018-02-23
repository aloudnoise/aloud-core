<?php 
use common\components\Migration;

class m180112_165303_pupil_custom_fields_for_gos_organizaionts extends Migration
{
    public function safeUp()
    {
        $this->insert('dic_values', [
            'dic' => 'pupil_custom_fields',
            'value' => 'rank',
            'name' => '{"ru-RU":"Должность","kk-KZ":"Лауазымы"}',
            'organization_id' => null,
            'organization_type' => \common\models\Organizations::TYPE_GOS_SYSTEM
        ]);

        $this->insert('dic_values', [
            'dic' => 'pupil_custom_fields',
            'value' => 'division',
            'name' => '{"ru-RU":"Подразделение","kk-KZ":"Бөлімше"}',
            'organization_id' => null,
            'organization_type' => \common\models\Organizations::TYPE_GOS_SYSTEM
        ]);

    }

    public function safeDown()
    {
        echo "m180112_165303_pupil_custom_fields_for_gos_organizaionts cannot be reverted.\n";
        return false;
    }
}
