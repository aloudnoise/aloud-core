<?php 
use common\components\Migration;

class m180103_083746_add_chats_table extends Migration
{
    public function safeUp()
    {

        $this->createTable("chats", [
            'id' => $this->primaryKey(),
            'organization_id' => $this->integer()->notNull(),
            'name' => $this->string(1000),
            'type' => $this->smallInteger()->notNull()->defaultValue(1),
            'user_id' => $this->integer(),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'info' => $this->jsonb(),
        ]);

        $this->createTable("relations.chat_user", [
        ], "INHERITS (relations.relations_template)");

        $this->addColumn("relations.chat_user", "role", $this->smallInteger()->notNull()->defaultValue(1));

        $this->createTable("messages", [
            'id' => $this->primaryKey(),
            'organization_id' => $this->integer()->notNull(),
            'chat_id' => $this->integer(),
            'user_id' => $this->integer()->notNull(),
            'message' => $this->string(2000)->notNull(),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'info' => $this->jsonb(),
        ]);

        $this->createTable("relations.message_user_status", [
        ], "INHERITS (relations.relations_template)");

        $this->addColumn("relations.message_user_status", "status", $this->smallInteger()->notNull()->defaultValue(0));

        $this->createIndex("messages_by_chat_id", "messages", "chat_id");

        $this->addForeignKey("messages_chat_id_fk", "messages", "chat_id", "chats", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("chat_user_chat_fk", "relations.chat_user", "target_id", "chats", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("chat_user_user_fk", "relations.chat_user", "related_id", "users", "id", "CASCADE", "CASCADE");

        $this->addForeignKey("message_user_message_fk", "relations.message_user_status", "target_id", "messages", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("message_user_user_fk", "relations.message_user_status", "related_id", "users", "id", "CASCADE", "CASCADE");

        $this->createTable("relations.event_chat", [
        ], "INHERITS (relations.relations_template)");

        $this->addForeignKey("event_chat_event_fk", "relations.event_chat", "target_id", "events", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("event_chat_chat_fk", "relations.event_chat", "related_id", "chats", "id", "CASCADE", "CASCADE");


    }

    public function safeDown()
    {
        echo "m180103_083746_add_chats_table cannot be reverted.\n";
        return false;
    }
}
