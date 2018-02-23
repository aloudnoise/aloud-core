<?php 
use common\components\Migration;

class m180116_162529_tasks_add_finished extends Migration
{
    public function safeUp()
    {
        $this->dropColumn("tasks", "is_controllable");
    }

    public function safeDown()
    {
        echo "m180116_162529_tasks_add_finished cannot be reverted.\n";
        return false;
    }
}
