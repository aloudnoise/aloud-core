<?php 
use common\components\Migration;

class m170808_134421_add_dic extends Migration
{
    public function safeUp()
    {

        $this->insert("dics", [
            "name" => "DicQuestionThemes"
        ]);

    }

    public function safeDown()
    {
        echo "m170808_134421_add_dic cannot be reverted.\n";
        return false;
    }
}
