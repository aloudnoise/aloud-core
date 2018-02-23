<?php 
use common\components\Migration;

class m180124_103015_asd extends Migration
{
    public function safeUp()
    {

        $this->createTable('education_plans', [
            'id' => $this->primaryKey(),
            'name' => $this->string(500)->notNull(),
            'organization_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'begin_ts' => $this->timestamptz()->notNull(),
            'end_ts' => $this->timestamptz()->notNull(),
            'info' => $this->jsonb(),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'is_deleted' => $this->smallInteger()->notNull()->defaultValue(0)
        ]);

    }

    public function safeDown()
    {
        echo "m180124_103015_asd cannot be reverted.\n";
        return false;
    }
}
