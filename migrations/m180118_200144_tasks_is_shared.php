<?php 
use common\components\Migration;

class m180118_200144_tasks_is_shared extends Migration
{
    public function safeUp()
    {
        $this->addColumn("tasks", "is_shared", $this->smallInteger()->notNull()->defaultValue(1));
    }

    public function safeDown()
    {
        echo "m180118_200144_tasks_is_shared cannot be reverted.\n";
        return false;
    }
}
