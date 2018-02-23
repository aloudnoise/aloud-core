<?php 
use common\components\Migration;

class m180122_085355_alter_test_task_results extends Migration
{
    public function safeUp()
    {
        $this->addColumn("results.test_results", "status", $this->smallInteger()->notNull()->defaultValue(1));
        $this->addColumn("results.task_results", "status", $this->smallInteger()->notNull()->defaultValue(1));
    }

    public function safeDown()
    {
        echo "m180122_085355_alter_test_task_results cannot be reverted.\n";
        return false;
    }
}
