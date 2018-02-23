<?php
namespace app\modules\tests\models\constructor;

use app\models\Answers;
use app\models\Questions;
use app\models\relations\TestQuestion;
use common\components\Model;

class SimpleQuestion extends Base
{

    public $id = null;
    public $name = null;
    public $answers = [];
    public $weight = 1;
    public $is_random = 0;

    public function rules()
    {
        return [
            [['name','answers','weight'], 'required'],
            [['theme'], 'safe'],
            [['id', 'theme_id'], 'integer'],
            [['is_random'], 'filter', 'filter' => function($value) {
                return $value ? 1 : 0;
            }],
            [['is_random'], 'integer'],
            [['weight'], 'number'],
            [['answers'], function() {
                if (!empty($this->answers) AND is_array($this->answers)) {

                    $is_correct = 0;
                    foreach ($this->answers as $answer) {

                        if (empty($answer['name'])) {
                            $this->addError("answers", \Yii::t("main","Текст вариантов ответов не может быть пустым"));
                            return false;
                        }

                        if ($answer['is_correct'] == 1) {
                            $is_correct = 1;
                        }
                    }

                    if (!$is_correct) {
                        $this->addError("answers", \Yii::t("main","Укажите хотябы один правильный вариант"));
                        return false;
                    }

                    return true;
                }
                return false;
            }]
        ];
    }

    public function save()
    {

        if (!$this->validate()) return false;

        $transaction = \Yii::$app->db->beginTransaction();

        $question = new Questions();
        if ($this->id) {
            $question = Questions::find()->byOrganization()->byPk($this->id)->one();
            if (!$question) {
                $this->addError("name", "NO_QUESTION_FOUND");
                $transaction->rollBack();
                return false;
            }
        }
        $question->name = $this->name;
        $question->is_random = $this->is_random;
        $question->theme_id = $this->theme_id;
        $question->type = 1;
        $question->weight = $this->weight;

        if (!$question->save()) {
            $this->addErrors($question->getErrors());
            $transaction->rollBack();
            return false;
        }

        $exist_answers = Answers::find()->andWhere([
            'question_id' => $question->id
        ])->indexBy('id')->byOrganization()->all();

        foreach ($this->answers as $ans) {

            $answer = new Answers();
            $answer->question_id = $question->id;
            if (!empty($ans['id'])) {
                $answer = Answers::find()->byPk($ans['id'])->byOrganization()->one();
                if (!$answer) {
                    $this->addError("name", "NO_ANSWER_FOUND");
                    $transaction->rollBack();
                    return false;
                }
            }

            $answer->name = $ans['name'];
            $answer->is_correct = $ans['is_correct'];

            if (!$answer->save()) {
                $this->addErrors($answer->getErrors());
                $transaction->rollBack();
                return false;
            }

            unset($exist_answers[$answer->id]);
        }

        if (!empty($exist_answers)) {
            foreach ($exist_answers as $ea) {
                $ea->delete();
            }
        }

        if (!$this->id AND $this->test) {
            $relation = new TestQuestion();
            $relation->target_id = $this->test->id;
            $relation->related_id = $question->id;
            if (!$relation->save()) {
                $this->addErrors($relation->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

    public function loadAttributes($question)
    {
        $this->id = $question->id;
        $this->attributes = $question->attributes;
        foreach ($question->answers as $answer) {
            $this->answers[] = $answer->attributes;
        }
    }

}