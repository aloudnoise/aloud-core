<?php 
use common\components\Migration;

class m171110_081240_alter_bilimal_user_organization extends Migration
{
    public function safeUp()
    {

        $this->addColumn("bilimal_user", "user_type", $this->smallInteger()->notNull()->defaultValue(1));
        $this->addColumn("bilimal_organization", "institute_type", $this->smallInteger()->notNull()->defaultValue(1));

    }

    public function safeDown()
    {
        echo "m171110_081240_alter_bilimal_user_organization cannot be reverted.\n";
        return false;
    }
}
