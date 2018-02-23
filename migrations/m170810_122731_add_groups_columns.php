<?php 
use common\components\Migration;

class m170810_122731_add_groups_columns extends Migration
{
    public function safeUp()
    {

        $this->addColumn("groups", "organization_id", $this->integer()->notNull());

    }

    public function safeDown()
    {
        echo "m170810_122731_add_groups_columns cannot be reverted.\n";
        return false;
    }
}
