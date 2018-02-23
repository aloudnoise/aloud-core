<?php 
use common\components\Migration;

class m171115_084246_example_model extends Migration
{
    public function safeUp()
    {
        $this->createTable("example", [
            'id' => $this->primaryKey(),
            'name' => $this->string(200),
            'info' => $this->jsonb(),
            'organization_id' => $this->integer()->notNull(),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()"))
        ]);
    }

    public function safeDown()
    {
        echo "m171115_084246_example_model cannot be reverted.\n";
        return false;
    }
}
