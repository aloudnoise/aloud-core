<?php 
use common\components\Migration;

class m171207_103445_add_criterias_to_results extends Migration
{
    public function safeUp()
    {
        $this->addColumn("results.test_results", "translated_result", $this->string(250));
    }

    public function safeDown()
    {
        echo "m171207_103445_add_criterias_to_results cannot be reverted.\n";
        return false;
    }
}
