<?php 
use common\components\Migration;

class m180116_182445_task_results_add_translated_result extends Migration
{
    public function safeUp()
    {
        $this->addColumn("results.task_results", "translated_result", $this->string(250));
    }

    public function safeDown()
    {
        echo "m180116_182445_task_results_add_translated_result cannot be reverted.\n";
        return false;
    }
}
