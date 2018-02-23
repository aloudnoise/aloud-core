<?php 
use common\components\Migration;

class m180116_161952_tasks_add_finished extends Migration
{
    public function safeUp()
    {
        $this->addColumn("results.task_results", "finished", $this->timestamptz()->defaultValue(null));
    }

    public function safeDown()
    {
        echo "m180116_161952_tasks_add_finished cannot be reverted.\n";
        return false;
    }
}
