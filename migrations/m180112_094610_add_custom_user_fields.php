<?php 
use common\components\Migration;

class m180112_094610_add_custom_user_fields extends Migration
{
    public function safeUp()
    {
        $this->insert("dics", [
            "name" => "pupil_custom_fields",
            "description" => "Дополнительные поля данных слушателей"
        ]);
    }

    public function safeDown()
    {
        echo "m180112_094610_add_custom_user_fields cannot be reverted.\n";
        return false;
    }
}
