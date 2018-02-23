<?php 
use common\components\Migration;

class m180116_162841_tasks_time extends Migration
{
    public function safeUp()
    {
        $this->addColumn("tasks", "time", $this->integer()->notNull()->defaultValue(30));
    }

    public function safeDown()
    {
        echo "m180116_162841_tasks_time cannot be reverted.\n";
        return false;
    }
}
