<?php 
use common\components\Migration;

class m180112_122539_groups_add_user_id extends Migration
{
    public function safeUp()
    {

        $this->addColumn("groups", "user_id", $this->integer()->notNull());

    }

    public function safeDown()
    {
        echo "m180112_122539_groups_add_user_id cannot be reverted.\n";
        return false;
    }
}
