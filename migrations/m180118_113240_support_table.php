<?php 
use common\components\Migration;

class m180118_113240_support_table extends Migration
{
    public function safeUp()
    {
        $this->createTable("support", [
            'id' => $this->primaryKey(),
            'organization_id' => $this->integer(),
            'user_id' => $this->integer(),
            'question' => $this->string(3000),
            'contacts' => $this->string(1000),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'info' => $this->jsonb(),
            'is_deleted' => $this->smallInteger()->notNull()->defaultValue(0)
        ]);
    }

    public function safeDown()
    {
        echo "m180118_113240_support_table cannot be reverted.\n";
        return false;
    }
}
