<?php 
use common\components\Migration;

class m180116_165848_event_task extends Migration
{
    public function safeUp()
    {
        $this->createTable("relations.event_task", [
        ], "inherits (relations.relations_template)");
    }

    public function safeDown()
    {
        echo "m180116_165848_event_task cannot be reverted.\n";
        return false;
    }
}
