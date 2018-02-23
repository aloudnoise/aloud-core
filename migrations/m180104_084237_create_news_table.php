<?php
use common\components\Migration;

/**
 * Handles the creation of table `news`.
 */
class m180104_084237_create_news_table extends Migration
{
    public function safeUp()
    {
        $this->dropTable("news");
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'organization_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'image' => $this->string(255),
            'content' => $this->text(),
            'type' => $this->smallInteger()->defaultValue(0),
            "user_id" => $this->integer()->notNull(),
            "is_deleted" => $this->smallInteger()->notNull()->defaultValue(0),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression('NOW()')),
            "info" => $this->jsonb(),
        ]);
    }

    public function safeDown()
    {
        echo "m180104_084237_create_news_table cannot be reverted.\n";
        return false;
    }
}
