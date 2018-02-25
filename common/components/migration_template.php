<?php
echo "<?php \n";
?>
use aloud_core\common\components\Migration;

class <?=$className?> extends Migration
{
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "<?=$className?> cannot be reverted.\n";
        return false;
    }
}
