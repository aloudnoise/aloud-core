<?php

namespace common\models\results;

use common\models\notifications\UserNotification;
use common\models\Tasks;
use common\models\Users;
use common\traits\AttributesToInfoTrait;
use yii\db\Expression;
use yii\web\User;

/**
 * This is the model class for table "results.task_results".
 *
 * @property integer $task_id
 * @property string $answer
 * @property string $result
 * @property integer $reviewer_id
 * @property integer $review_ts
 * @property string $translated_result
 * @property integer $status
 */
class TaskResults extends ResultsTemplate
{

    use AttributesToInfoTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_DISCARDED = 2;

    public function attributesToInfo()
    {
        return ['criteria', 'status_note'];
    }

    public static function find()
    {
        return parent::find()->andWhere([
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
        return 'results.task_results';
    }

    public static function getMyResult($task_id, $from = null)
    {
        return self::findFrom($from)
            ->andWhere([
                "task_id" => $task_id,
            ])
            ->byUser()
            ->andWhere("finished IS NOT NULL")
            ->orderBy("result
             DESC")
            ->one();
    }

    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ["id" => "task_id"]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['task_id'], 'required'],
            [['task_id', 'result'], 'integer'],
            [['result'], 'integer', 'min' => 0, 'max' => 100],
            [['status_note'], 'required', 'when' => function() {
                return $this->status == static::STATUS_DISCARDED;
            }],
            [['answer', 'note', 'status_note'], 'string', 'max' => 2000],
            [['result'], function() {
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
                $this->reviewer_id = \Yii::$app->user->id;
                $this->review_ts = time();
            }]
        ]);
    }

    public function getIsActive()
    {
        return $this->status == static::STATUS_ACTIVE;
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
        $dt_end->add(new \DateInterval('PT' . $this->task->time . 'M'));

        $cdt = new \DateTime('NOW');

        $t_end = strtotime($dt_end->format("d.m.Y H:i:s"));
        $ct = strtotime($cdt->format("d.m.Y H:i:s"));

        return $t_end - $ct;

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
        $this->finished = new Expression('NOW()');
        if (!$this->answer) {
            $this->result = 0;
        } else {

//            $notification = new UserNotification();
//            $notification->target_id = $this->user_id;
//            $notification->target_type = UserNotification::TARGET_USER;
//
//            $fromModel = $this->fromModel;
//            $notification->name = $this->from == 'event' ? $fromModel->name : $fromModel->course->
//            $notification->content = 'Слушатель ответил на задание "'.$this->task->name.'"';

        }
        return $this->save();
    }

    public function getReviewer()
    {
        return $this->hasOne(Users::className(), ['id' => 'reviewer_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {


        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

}
