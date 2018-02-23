<?php 
use common\components\Migration;

class m171228_082740_tasks_user_id extends Migration
{
    public function safeUp()
    {
        $this->addColumn("tasks", "user_id", $this->integer());
    }

    public function safeDown()
    {
        echo "m171228_082740_tasks_user_id cannot be reverted.\n";
        return false;
    }
}
