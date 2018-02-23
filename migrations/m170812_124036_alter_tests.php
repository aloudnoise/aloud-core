<?php 
use common\components\Migration;

class m170812_124036_alter_tests extends Migration
{
    public function safeUp()
    {

        $this->addColumn("tests", "is_repeatable", $this->smallInteger()->notNull()->defaultValue(0));

    }

    public function safeDown()
    {
        echo "m170812_124036_alter_tests cannot be reverted.\n";
        return false;
    }
}
