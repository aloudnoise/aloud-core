<?php 
use common\components\Migration;

class m170804_095449_bilimal_organization extends Migration
{
    public function safeUp()
    {

        $this->createTable("bilimal_organization", [
            "id" => $this->primaryKey(),
            "institution_id" => $this->integer()->notNull(),
            "organization_id" => $this->integer()->notNull(),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
            "is_deleted" => $this->smallInteger()->notNull()->defaultValue(0)
        ]);

        $this->createTable("bilimal_user", [
            "id" => $this->primaryKey(),
            "bilimal_user_id" => $this->integer()->notNull(),
            "user_id" => $this->integer()->notNull(),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
            "is_deleted" => $this->smallInteger()->notNull()->defaultValue(0)
        ]);

        $this->createTable("relations.user_organization", [
        ], "INHERITS (relations.relations_template)");

    }

    public function safeDown()
    {
        echo "m170804_095449_bilimal_organization cannot be reverted.\n";
        return false;
    }
}
