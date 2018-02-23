<?php 
use common\components\Migration;

class m180125_091617_add_dic_subjects extends Migration
{
    public function safeUp()
    {
        $this->insert("dics", [
            "name" => "subjects",
            "description" => "Предметы"
        ]);
    }

    public function safeDown()
    {
        echo "m180125_091617_add_dic_subjects cannot be reverted.\n";
        return false;
    }
}
