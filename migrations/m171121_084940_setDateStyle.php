<?php 
use common\components\Migration;

class m171121_084940_setDateStyle extends Migration
{
    public function safeUp()
    {

        $this->execute('ALTER DATABASE '.\Yii::$app->params['db']['name'].' SET datestyle TO "ISO, DMY";');
    }

    public function safeDown()
    {
        echo "m171121_084940_setDateStyle cannot be reverted.\n";
        return false;
    }
}
