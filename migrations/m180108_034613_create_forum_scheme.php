<?php 
use common\components\Migration;

class m180108_034613_create_forum_scheme extends Migration
{
    public function safeUp()
    {
        $sql = 'CREATE SCHEMA forum;';
        $this->execute($sql);
    }

    public function safeDown()
    {
        echo "m180108_034613_create_forum_scheme cannot be reverted.\n";
        return false;
    }
}
