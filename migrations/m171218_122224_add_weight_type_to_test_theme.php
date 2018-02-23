<?php 
use common\components\Migration;

class m171218_122224_add_weight_type_to_test_theme extends Migration
{
    public function safeUp()
    {
        $this->addColumn("relations.test_theme", "weight_type", $this->smallInteger()->notNull()->defaultValue(1));
    }

    public function safeDown()
    {
        echo "m171218_122224_add_weight_type_to_test_theme cannot be reverted.\n";
        return false;
    }
}
