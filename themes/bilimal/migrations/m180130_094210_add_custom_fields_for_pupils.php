<?php 
use common\components\Migration;

class m180130_094210_add_custom_fields_for_pupils extends Migration
{
    public function safeUp()
    {
        $this->insert('dic_values', [
            'dic' => 'pupil_custom_fields',
            'value' => 'group_name',
            'name' => 'Группа',
            'organization_id' => null,
            'organization_type' => \bilimal\web\models\Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM
        ]);

        $this->insert('dic_values', [
            'dic' => 'pupil_custom_fields',
            'value' => 'group_course',
            'name' => 'Курс',
            'organization_id' => null,
            'organization_type' => \bilimal\web\models\Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM
        ]);

        $this->insert('dic_values', [
            'dic' => 'pupil_custom_fields',
            'value' => 'speciality',
            'name' => 'Специальность',
            'organization_id' => null,
            'organization_type' => \bilimal\web\models\Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM
        ]);

        $this->insert('dic_values', [
            'dic' => 'pupil_custom_fields',
            'value' => 'group_name',
            'name' => 'Класс',
            'organization_id' => null,
            'organization_type' => \bilimal\web\models\Organizations::TYPE_BILIMAL_SCHOOL_SYSTEM
        ]);

        $this->insert('dic_values', [
            'dic' => 'pupil_custom_fields',
            'value' => 'group_course',
            'name' => 'Паралель',
            'organization_id' => null,
            'organization_type' => \bilimal\web\models\Organizations::TYPE_BILIMAL_SCHOOL_SYSTEM
        ]);

    }

    public function safeDown()
    {
        echo "m180130_094210_add_custom_fields_for_pupils cannot be reverted.\n";
        return false;
    }
}
