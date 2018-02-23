<?php 
use common\components\Migration;

class m180108_133547_add_polls extends Migration
{
    public function safeUp()
    {

        $this->createTable("polls", [
            "id" => $this->primaryKey(),
            "name" => $this->string(1000)->notNull(),
            "user_id" => $this->integer()->notNull(),
            'organization_id' => $this->integer()->notNull(),
            "info" => $this->jsonb(),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            'is_deleted' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)
        ]);

        $this->createTable("results.poll_results", [
            "id" => $this->primaryKey(),
            "poll_id" => $this->integer()->notNull(),
            "user_id" => $this->integer()->notNull(),
            "result" => $this->string(200)->notNull(),
            "info" => $this->jsonb(),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()'))
        ]);

        $this->addForeignKey("results_poll_id_fk", "results.poll_results", "poll_id", "polls", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("results_user_id_fk", "results.poll_results", "user_id", "users", "id", "CASCADE", "CASCADE");


    }

    public function safeDown()
    {
        echo "m180108_133547_add_polls cannot be reverted.\n";
        return false;
    }
}
