<?php 
use common\components\Migration;

class m180206_124253_add_event_organization extends Migration
{
    public function safeUp()
    {
        $this->createTable('relations.event_organization', [
        ], "inherits (relations.relations_template)");
    }

    public function safeDown()
    {
        echo "m180206_124253_add_event_organization cannot be reverted.\n";
        return false;
    }
}
