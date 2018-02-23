<?php 
use common\components\Migration;

class m180218_160638_log_table_type extends Migration
{
    public function safeUp()
    {
        $this->addColumn('logs', 'type', $this->string(20));
    }

    public function safeDown()
    {
        echo "m180218_160638_log_table_type cannot be reverted.\n";
        return false;
    }
}
