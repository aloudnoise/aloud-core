<?php 
use common\components\Migration;

class m180117_075002_add_theme_id_event_theme extends Migration
{
    public function safeUp()
    {
        $this->addColumn("materials", "theme_id", $this->integer());
        $this->dropColumn("materials", "theme");
        $this->addForeignKey("materials_theme_id", "materials", "theme_id", "dic_values", "id", "SET NULL", "CASCADE");

        $this->createTable("relations.event_theme", [], "inherits (relations.relations_template)");
        $this->addForeignKey("event_theme_fk", "relations.event_theme", "related_id", "dic_values", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("event_theme_event_fk", "relations.event_theme", "target_id", "events", "id", "CASCADE", "CASCADE");

    }

    public function safeDown()
    {
        echo "m180117_075002_add_theme_id_event_theme cannot be reverted.\n";
        return false;
    }
}
