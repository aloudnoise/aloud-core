<?php 
use common\components\Migration;

class m180116_174135_answer_drop_not_null extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE results.task_results ALTER COLUMN answer DROP NOT NULL;");
    }

    public function safeDown()
    {
        echo "m180116_174135_answer_drop_not_null cannot be reverted.\n";
        return false;
    }
}
