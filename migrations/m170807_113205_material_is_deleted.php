<?php 
use common\components\Migration;

class m170807_113205_material_is_deleted extends Migration
{
    public function safeUp()
    {
        $this->addColumn("materials", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("answers", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("course_lessons", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("courses", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("dic_values", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("dics", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("events", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("news", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("questions", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("tasks", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("tests", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
        $this->addColumn("relations.relations_template", "is_deleted", $this->smallInteger()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        echo "m170807_113205_material_is_deleted cannot be reverted.\n";
        return false;
    }
}
