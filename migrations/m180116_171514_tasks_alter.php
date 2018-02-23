<?php 
use common\components\Migration;

class m180116_171514_tasks_alter extends Migration
{
    public function safeUp()
    {
        $this->dropColumn("results.task_results", "result");
        $this->addColumn("results.task_results", "result", $this->integer());
        $this->addColumn("results.task_results", "note", $this->string(2000));
    }

    public function safeDown()
    {
        echo "m180116_171514_tasks_alter cannot be reverted.\n";
        return false;
    }
}
