<?php 
use common\components\Migration;

class m171227_191827_language extends Migration
{
    public function safeUp()
    {

        $this->addColumn("materials", "language", $this->string(10));

    }

    public function safeDown()
    {
        echo "m171227_191827_language cannot be reverted.\n";
        return false;
    }
}
