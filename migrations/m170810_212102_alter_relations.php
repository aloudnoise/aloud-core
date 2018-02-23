<?php 
use common\components\Migration;

class m170810_212102_alter_relations extends Migration
{
    public function safeUp()
    {
        $this->execute("update relations.relations_template SET state = 1 WHERE state IS NULL");
        $this->execute("ALTER TABLE relations.relations_template ALTER COLUMN state SET DEFAULT 1;");
    }

    public function safeDown()
    {
        echo "m170810_212102_alter_relations cannot be reverted.\n";
        return false;
    }
}
