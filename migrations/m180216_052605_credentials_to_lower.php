<?php 
use common\components\Migration;

class m180216_052605_credentials_to_lower extends Migration
{
    public function safeUp()
    {
        $this->execute("UPDATE auth_credentials set credential = lower(credential);");
    }

    public function safeDown()
    {
        echo "m180216_052605_credentials_to_lower cannot be reverted.\n";
        return false;
    }
}
