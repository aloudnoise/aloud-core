<?php 
use common\components\Migration;

class m171205_174718_add_test_criterias extends Migration
{
    public function safeUp()
    {
        $this->insert('dics', [
            'name' => "test_criteria_templates"
        ]);
    }

    public function safeDown()
    {
        echo "m171205_174718_add_test_criterias cannot be reverted.\n";
        return false;
    }
}
