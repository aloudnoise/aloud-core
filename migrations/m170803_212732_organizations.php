<?php 
use common\components\Migration;

class m170803_212732_organizations extends Migration
{
    public function safeUp()
    {

        $this->createTable("organizations", [
            "id" => $this->primaryKey(),
            "name" => $this->string(255),
            "type" => $this->smallInteger()->notNull(),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
            "is_deleted" => $this->smallInteger()->notNull()->defaultValue(0),
            "info" => $this->jsonb()
        ]);



    }

    public function safeDown()
    {
        echo "m170803_212732_organizations cannot be reverted.\n";
        return false;
    }
}
