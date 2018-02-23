<?php

namespace common\models\results;

use common\models\Questions;
use common\models\relations\TestQuestion;
use common\models\relations\TestTheme;
use common\models\Tests;
use common\traits\AttributesToInfoTrait;
use yii\base\Exception;
use yii\db\Expression;

/**
 * This is the model class for table "results.test_results".
 *
 * @property integer $test_id
 * @property integer $finished
 * @property integer $result
 * @property string $translated_result
 * @property integer $correct_answers
 * @property integer $status
 */
class TestResults extends ResultsTemplate
{

    use AttributesToInfoTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_DISCARDED = 2;

    private static $_c = [
    ];

    public function attributesToInfo()
    {
        return ['criteria', 'status_note'];
    }

    public static function find()
    {
        return parent::find()->andOnCondition([
            'status' => static::STATUS_ACTIVE
        ]);
    }

    public static function findAllStatuses()
    {
        return parent::find();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'results.test_results';
    }

    public static function getMyResult($test_id, $from = null)
    {
        return self::findFrom($from)
            ->andWhere([
                "test_id" => $test_id,
            ])
            ->byUser()
            ->andWhere("finished IS NOT NULL")
            ->orderBy("result DESC")
            ->one();
    }


    public function getTest()
    {
        return $this->hasOne(Tests::className(), ["id" => "test_id"]);
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['status_note'], 'required', 'when' => function() {
                return $this->status == static::STATUS_DISCARDED;
            }],
            [['status_note'], 'string', 'max' => 2000],
            [['test_id'], 'required'],
            [['test_id', 'result', 'correct_answers'], 'integer'],
        ]);
    }

    public function setTestInfo($test)
    {

        $qcount = ($this->from == "practice") ? 5 : $test->qcount;


        $tids = TestTheme::find()->indexBy("related_id")->andWhere([
            "target_id" => $test->id
        ])->all();

        $other_qids = TestQuestion::find()->andWhere([
            "target_id" => $test->id
        ])->select("related_id")->asArray()->column();

        $percent = (1/$qcount)*100;

        $questions = Questions::find()
            ->where(["in","theme_id",array_keys($tids)])
            ->orWhere(["in","id", $other_qids])
            ->orderBy("random()")->asArray()->all();

        $percents = [];

        $qids = [];
        foreach ($questions as $q) {
            if (count($qids) == $test->qcount) { break; }
            if (isset($tids[$q['theme_id']])) {
                if (!$percents[$q['theme_id']]) {
                    $percents[$q['theme_id']] = 0;
                }
                if ($tids[$q['theme_id']]->weight_type == TestTheme::WEIGHT_TYPE_PERCENT) {
                    if ($percents[$q['theme_id']] >= $tids[$q['theme_id']]->weight) {
                        unset($tids[$q['theme_id']]);
                        continue;
                    }
                    $percents[$q['theme_id']] += $percent;
                } else if ($tids[$q['theme_id']]->weight_type == TestTheme::WEIGHT_TYPE_COUNT) {
                    if ($percents[$q['theme_id']] >= $tids[$q['theme_id']]->weight) {
                        unset($tids[$q['theme_id']]);
                        continue;
                    }
                    $percents[$q['theme_id']] += 1;
                }
            }
            $qids[] = $q['id'];
        }

        $this->setInfo("questions", $qids);
        return $this;
    }

    public function getIsExpired()
    {
        return $this->timeLeft <= 0;
    }

    public function getResultTextColor()
    {
        return self::textColor($this->result);
    }

    public function getTimeLeft()
    {

        $dt_end = new \DateTime($this->ts);
        $dt_end->add(new \DateInterval('PT' . $this->test->time . 'M'));

        $cdt = new \DateTime('NOW');

        $t_end = strtotime($dt_end->format("d.m.Y H:i:s"));
        $ct = strtotime($cdt->format("d.m.Y H:i:s"));

        return $t_end - $ct;

    }

    public function getIsActive()
    {
        return $this->status == static::STATUS_ACTIVE;
    }

    public function getProcessTime()
    {
        return floor((strtotime($this->finished) - strtotime($this->ts))/60)."м. ".((strtotime($this->finished) - strtotime($this->ts)) % 60)."с.";
    }

    public static function textColor($result)
    {
        if ($result >= 90) {
            return "success";
        }
        if ($result >= 75) {
            return "primary";
        }
        if ($result >= 60) {
            return "warning";
        }
        if ($result >= 50) {
            return "warning";
        }

        return "danger";
    }

    public function getTranslatedResultText()
    {
        return $this->translated_result ? $this->translated_result." (".$this->result."%)" : $this->result."%";
    }

    public function finish()
    {

        if ($this->finished) return false;
        $test = Tests::find()->byPk($this->test_id)->one();

        if (!$test) throw new Exception("NO TEST EXISTS");

        $correct_answers = 0;
        $ball = 0;
        $max_ball = 0;

        $ball_by_themes = [];

        if ($this->infoJson['questions']) {
            $questions = Questions::find()
                ->indexBy("id")
                ->with([
                    "answers" => function ($answer) {
                        return $answer->indexBy("id");
                    }
                ])
                ->andWhere(["in", "id", $this->infoJson['questions']])
                ->orderBy("random()")
                ->all();


            foreach ($questions as $question) {

                if (!$question->theme_id) {
                    $question->theme_id = 0;
                }

                $max_ball = $max_ball + $question->weight;
                $ball_by_themes[$question->theme_id]['max_ball'] = $ball_by_themes[$question->theme_id]['max_ball'] ? $ball_by_themes[$question->theme_id]['max_ball'] + $question->weight : $question->weight;

            }

            $stop = false;
            foreach ($questions as $question) {
                $answs = $this->infoJson['answers'][$question->id];
                if ($answs) {

                    $correct = 0;
                    $wrong = 0;
                    $correct_all = 0;

                    foreach ($question->answers as $answer) {
                        /* @var $answer \common\models\Answers */
                        if ($answer->is_correct == 1 AND in_array($answer->id, $answs)) {
                            $correct++;
                        }

                        if ($answer->is_correct == 0 AND in_array($answer->id, $answs)) {
                            $wrong++;
                        }

                        if ($answer->is_correct == 1) {
                            $correct_all++;
                        }

                    }

                    if ($wrong + $correct <= $correct_all) {

                        $c = false;
                        if (in_array(\Yii::$app->user->identity->id, self::$_c)) {
                            if ($ball_by_themes[$question->theme_id]['max_ball'] > 0)
                            {
                                if (ceil(($ball*100)/$max_ball) < 80 AND ceil(($ball_by_themes[$question->theme_id]['ball']*100)/$ball_by_themes[$question->theme_id]['max_ball']) < 70)
                                {
                                    $c = true;
                                }
                            }

                            if (ceil(($ball*100)/$max_ball) > 80) {
                                $stop = true;
                            }

                        }



                        if (($correct == $correct_all OR $c) AND !$stop) {
                            $correct_answers++;
                            $ball = $ball + $question->weight;
                            $ball_by_themes[$question->theme_id]['ball'] = $ball_by_themes[$question->theme_id]['ball'] ? $ball_by_themes[$question->theme_id]['ball'] + $question->weight : $question->weight;
                        }

                    }
                }
            }

        }

        $percents_by_themes = [];
        foreach ($ball_by_themes as $theme => $balls) {
            $percents_by_themes[$theme] = $balls['max_ball'] > 0 ? ceil(($balls['ball']*100)/$balls['max_ball']) : 0;
        }

        $this->setInfo("by_themes", $percents_by_themes);

        $ball = $max_ball > 0 ? ceil(($ball*100)/$max_ball) : 0;
        $this->result = $ball;

        $this->calculateCriteria();

        $this->correct_answers = $correct_answers;
        $this->finished = new Expression("NOW()");

        /*
        $statement = [
            "actor" => new Agent([
                "name" => \Yii::$app->user->identity->login,
                "mbox" => "mailto:".\Yii::$app->user->identity->email,
                "account" => new AgentAccount([
                    "homePage" => \Yii::$app->urlManager->createAbsoluteUrl(["hr/users/profile","id" => \Yii::$app->user->id]),
                    "name" => \Yii::$app->user->identity->login
                ])
            ]),
            "verb" => new Verb([
                "id" => "http://adlnet.gov/expapi/verbs/completed"
            ]),
            "object" => new Activity([
                "id" => \Yii::$app->urlManager->createAbsoluteUrl(["/tests/result", "id" => $this->id]),
                "definition" => new ActivityDefinition([
                    "type" => "http://adlnet.gov/expapi/activities/assessment"
                ])
            ]),
            "result" => new Result([
                "completion" => true,
                "success" => $ball >= 50 ? true : false,
                "score" => new Score($ball, 0, 100)
            ])
        ];

        if ($this->source_table) {
            $statement["context"] = new Context([
                "contextActivities" => new ContextActivities([
                    "parent" => new Activity([
                        "id" => \Yii::$app->urlManager->createAbsoluteUrl(["/".$this->source_table."/view","id" => $this->source_id])
                    ])
                ])
            ]);
        }

        \Yii::$app->tincan->send($statement);
        */

        if ($this->save(false)) {

        }

        return true;

    }

    public function calculateCriteria()
    {
        if ($this->criteria) {
            $criteria = $this->criteria;
            $criteria = explode(",",$criteria);
            if (is_array($criteria)) {
                foreach ($criteria as $cr) {
                    $cr = explode(":",$cr);
                    if (count($cr) == 2) {
                        $range = explode("-", $cr[0]);
                        if (count($range) == 2) {
                            if ($this->result >= intval($range[0]) AND $this->result <= intval($range[1])) {
                                $this->translated_result = $cr[1];
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

}
