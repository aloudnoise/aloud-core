<?php 
use common\components\Migration;

class m171214_095915_add_fk_to_questions extends Migration
{
    public function safeUp()
    {

        $qids = \common\models\Questions::find()->select(['id'])->asArray()->column();
        \common\models\Answers::deleteAll([
            'not in', 'question_id', $qids
        ]);

        $this->addForeignKey("question_id_fk", "answers", "question_id", "questions", "id", "CASCADE", "CASCADE");

    }


    public function safeDown()
    {
        echo "m171214_095915_add_fk_to_questions cannot be reverted.\n";
        return false;
    }
}
