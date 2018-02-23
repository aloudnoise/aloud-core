<?php 
use common\components\Migration;

class m180218_124557_log_table extends Migration
{
    public function safeUp()
    {
        $this->createTable("logs", [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'organization_id' => $this->integer(),
            'route' => $this->string(3000),
            'info' => $this->jsonb(),
            'ip' => $this->string(100),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()'))
        ]);
    }

    public function safeDown()
    {
        echo "m180218_124557_log_table cannot be reverted.\n";
        return false;
    }
}
