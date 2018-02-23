<?php 
use common\components\Migration;

class m171228_080213_materials_is_shared extends Migration
{
    public function safeUp()
    {
        $this->execute("update materials set is_shared=1");
    }

    public function safeDown()
    {
        echo "m171228_080213_materials_is_shared cannot be reverted.\n";
        return false;
    }
}
