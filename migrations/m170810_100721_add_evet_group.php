<?php 
use common\components\Migration;

class m170810_100721_add_evet_group extends Migration
{
    public function safeUp()
    {
        $this->createTable("groups", [
            "id" => $this->primaryKey(),
            "name" => $this->string(255),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
            "is_deleted" => $this->smallInteger()->notNull()->defaultValue(0),
            "info" => $this->jsonb()
        ]);

        $this->createTable("relations.group_user", [
            "group_role" => $this->string(100)->notNull()->defaultValue("member")
        ], "inherits (relations.relations_template)");

        $this->createTable("relations.event_group", [], "inherits (relations.relations_template)");

        $this->createTable("bilimal_group", [
            "id" => $this->primaryKey(),
            "bilimal_group_id" => $this->integer()->notNull(),
            "group_id" => $this->integer()->notNull(),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
            "is_deleted" => $this->smallInteger()->notNull()->defaultValue(0)
        ]);

    }

    public function safeDown()
    {
        echo "m170810_100721_add_evet_group cannot be reverted.\n";
        return false;
    }
}
