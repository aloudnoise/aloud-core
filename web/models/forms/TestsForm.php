<?php
namespace app\models\forms;

use app\models\Answers;
use app\models\Questions;
use app\models\relations\TestQuestion;
use app\models\relations\TestTheme;
use app\models\Tests;
use app\traits\BackboneRequestTrait;
use common\components\Model;

class TestsForm extends Model
{

    use BackboneRequestTrait;

    public $test = null;
    public $themes = [];
    public $questionsJson = null;
    public $themesJson = null;
    public $questions = [];

    public function rules()
    {
        return [
            [['themes','questions'], 'safe']
        ];
    }

    public function save()
    {
        if ($this->validate()) {

            $transaction = \Yii::$app->db->beginTransaction();

            $ids = [];
            if ($this->test->type == Tests::TYPE_FROM_QUESTIONS) {

                if (empty($this->questions)) {
                    $this->addError("questions", \Yii::t("main","Укажите хотябы один вопрос"));
                    return false;
                }

                foreach ($this->questions as $q) {
                    $question = json_decode($q, true);
                    if (is_array($question)) {
                        if (!$question['id']) {
                            $qm = Questions::find()->andWhere([
                                "name" => $question['name']
                            ])->one();

                            if (!$qm) {

                                $qm = new Questions();
                                $qm->name = $question['name'];
                                $qm->type = 1;
                                $qm->weight = 1;

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
                            $ids[] = $qm->id;
                        } else {
                            $ids[] = $question['id'];
                        }
                    } else if (is_numeric($question)) {
                        $ids[] = $question;
                    }
                }

                $ids = array_unique($ids);

                $assigned_ids = TestQuestion::find()->select("related_id")->andWhere([
                    "target_id" => $this->test->id
                ])->column();

                $add = array_diff($ids, $assigned_ids);

                foreach ($add as $add_id) {
                    $assign = new TestQuestion();
                    $assign->target_id = $this->test->id;
                    $assign->related_id = $add_id;
                    if (!$assign->save()) {
                        $this->addErrors($assign->getErrors());
                        $transaction->rollBack();
                        return false;
                    }
                }

                $remove = array_diff($assigned_ids, $ids);

                foreach ($remove as $remove_id) {
                    TestQuestion::deleteAll([
                        "target_id" => $this->test->id,
                        "related_id" => $remove_id
                    ]);
                }

                $transaction->commit();
                return true;
            } else if ($this->test->type == Tests::TYPE_FROM_THEMES) {

                if (empty($this->themes)) {
                    $this->addError("themes", \Yii::t("main","Укажите хотябы одну тему"));
                    return false;
                }

                foreach ($this->themes as $t) {
                    $theme = json_decode($t, true);
                    if (is_array($theme)) {
                        $ids[] = $theme['related_id'];
                        $id = $theme['related_id'];
                    } else if (is_numeric($theme)) {
                        $ids[] = $theme;
                        $id = $theme;
                    }

                    $assign = TestTheme::find()->andWhere([
                        "target_id" => $this->test->id,
                        "related_id" => $id
                    ])->one();
                    if (!$assign) $assign = new TestTheme();

                    $assign->target_id = $this->test->id;
                    $assign->related_id = $id;
                    if ($theme['weight']) {
                        $assign->weight = $theme['weight'];
                    }

                    if (!$assign->save()) {
                        $this->addErrors($assign->getErrors());
                        $transaction->rollBack();
                        return false;
                    }

                }

                $assigned_ids = TestTheme::find()->select("related_id")->andWhere([
                    "target_id" => $this->test->id
                ])->column();

                $remove = array_diff($assigned_ids, $ids);

                foreach ($remove as $remove_id) {
                    TestTheme::deleteAll([
                        "target_id" => $this->test->id,
                        "related_id" => $remove_id
                    ]);
                }

                $transaction->commit();
                return true;

            }

        }
        return false;
    }


}

?>