<?php 
use common\components\Migration;

class m171225_120029_add_notifications_table extends Migration
{
    public function safeUp()
    {
        $this->createTable("notifications", [
            'id' => $this->primaryKey(),
            'organization_id' => $this->integer()->notNull(),
            'target_id' => $this->integer()->notNull(),
            'target_type' => $this->string(250)->notNull(),
            'channels' => $this->string(4)->notNull()->defaultValue('0001'),
            'info' => $this->jsonb()->notNull(),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
            'is_deleted' => $this->smallInteger()->notNull()->defaultValue(0)
        ]);

        $this->createTable("relations.notification_user_status", [
        ], "INHERITS (relations.relations_template)");
        $this->addColumn("relations.notification_user_status", "status", $this->smallInteger()->notNull());


    }

    public function safeDown()
    {
        echo "m171225_120029_add_notifications_table cannot be reverted.\n";
        return false;
    }
}
