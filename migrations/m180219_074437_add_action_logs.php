<?php 
use common\components\Migration;

class m180219_074437_add_action_logs extends Migration
{
    public function safeUp()
    {
        $this->addColumn("logs", "referer", $this->string(3000));
        $this->addColumn("logs", "action", $this->string(100));
    }

    public function safeDown()
    {
        echo "m180219_074437_add_action_logs cannot be reverted.\n";
        return false;
    }
}
