<?php 
use common\components\Migration;

class m170812_131343_alter_questions extends Migration
{
    public function safeUp()
    {
        $this->execute("update questions SET weight = 1 WHERE weight IS NULL");
        $this->execute("ALTER TABLE questions ALTER COLUMN weight SET DEFAULT 1;");
    }

    public function safeDown()
    {
        echo "m170812_131343_alter_questions cannot be reverted.\n";
        return false;
    }
}
