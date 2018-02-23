<?php 
use common\components\Migration;

class m180220_072434_materials_unregistered_sharing extends Migration
{
    public function safeUp()
    {
        $this->addColumn("materials", "access_by_link", $this->smallInteger()->defaultValue(0));
    }

    public function safeDown()
    {
        echo "m180220_072434_materials_unregistered_sharing cannot be reverted.\n";
        return false;
    }
}
