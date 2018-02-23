<?php 
use common\components\Migration;

class m180113_200029_add_education_views extends Migration
{
    public function safeUp()
    {
        $this->insert("dics", [
            "name" => "education_views",
            "description" => "Виды обучения"
        ]);
    }

    public function safeDown()
    {
        echo "m180113_200029_add_education_views cannot be reverted.\n";
        return false;
    }
}
