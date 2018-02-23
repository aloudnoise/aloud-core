<?php 
use common\components\Migration;

class m171127_114128_add_sequence_to_lesson_task_table extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE ONLY relations.lesson_task ALTER COLUMN id SET DEFAULT nextval('\"relations\".relations_template_id_seq'::regclass);");
    }

    public function safeDown()
    {
        echo "m171127_114128_add_sequence_to_lesson_task_table cannot be reverted.\n";
        return false;
    }
}
