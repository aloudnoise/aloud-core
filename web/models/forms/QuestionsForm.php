<?php

namespace app\models\forms;

use app\models\Answers;
use app\models\Dics;
use app\models\DicValues;
use app\models\Questions;
use app\traits\BackboneRequestTrait;

class QuestionsForm extends \common\models\forms\QuestionsForm
{

    use BackboneRequestTrait;

    public $theme_name = "";
    public $questions = [];

    public function rules()
    {
        return [
            [['theme_name'], 'safe'],
            [['theme_name'], 'required', 'message' => \Yii::t("main","Укажите тему")],
            [['questions'], 'required', 'message' => \Yii::t("main","Добавьте хотябы 1 вопрос")]
        ];
    }

    public function save()
    {
        if ($this->validate()) {

            $transaction = \Yii::$app->db->beginTransaction();

            $theme = DicValues::find()->byOrganizationOrNull()->andWhere([
                "name" => $this->theme_name,
                "dic" => 'DicQuestionThemes'
            ])->one();

            if (!$theme) {
                $theme = new DicValues();
                $theme->name = $this->theme_name;
                $theme->dic = 'DicQuestionThemes';
                if (!$theme->save()) {
                    $this->addErrors($theme->getErrors());
                    $transaction->rollBack();
                    return false;
                }
            }

            foreach ($this->questions as $q) {
                $question = json_decode($q, true);

                $qcheck = Questions::find()->andWhere([
                    "name" => $question['name'],
                    "theme_id" => $theme->id
                ])->one();

                if (!$qcheck) {

                    $qm = new Questions();
                    $qm->name = $question['name'];
                    $qm->weight = $question['weight'] ?: 1;
                    $qm->type = 1;
                    $qm->theme_id = $theme->id;

                    if (!$qm->save()) {
                        $this->addErrors($qm->getErrors());
                        $transaction->rollBack();
                        return false;
                    }

                    foreach ($question['answers'] as $a) {
                        $an = new Answers();
                        $an->name = $a['name'];
                        $an->is_correct = $a['is_correct'] ? 1 : 0;
                        $an->question_id = $qm->id;

                        if (!$an->save()) {
                            $this->addErrors($an->getErrors());
                            $transaction->rollBack();
                            return false;
                        }
                    }

                }

            }

            $transaction->commit();
            return true;

        }
        return false;
    }

}

?>