<?php 
use common\components\Migration;

class m170804_104031_user_organization extends Migration
{
    public function safeUp()
    {

        $this->createTable("relations.user_organization", [
        ], "INHERITS (relations.relations_template)");
        $this->addColumn("relations.user_organization", "role", $this->string(200)->notNull());

    }

    public function safeDown()
    {
        echo "m170804_104031_user_organization cannot be reverted.\n";
        return false;
    }
}
