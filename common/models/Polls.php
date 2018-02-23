<?php

namespace common\models;

use common\models\results\PollResults;
use common\traits\AttributesToInfoTrait;
use common\traits\TagsTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "polls".
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int $organization_id
 * @property string $info
 * @property string $ts
 * @property int $is_deleted
 * @property int $status
 */
class Polls extends \common\components\ActiveRecord
{

    use TagsTrait, AttributesToInfoTrait, UpdateInsteadOfDeleteTrait;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public function attributesToInfo()
    {
        return ['question', 'answers', 'results'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'polls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['info'], 'safe'],
            [['name'], 'string', 'max' => 1000],
            [['tagsString'], 'safe'],
            [['question'], 'string', 'max' => 1000],
            [['answers'], function() {
                if (!empty($this->answers) AND is_array($this->answers)) {

                    foreach ($this->answers as $answer) {

                        if (empty($answer['name'])) {
                            $this->addError("answers", \Yii::t("main","Текст вариантов ответов не может быть пустым"));
                            return false;
                        }

                    }

                    return true;
                }
                return false;
            }],
            [['status'], 'filter', 'filter' => function($v) {
                return $v ? 1 : 0;
            }]
        ];
    }

    public function getMyResult()
    {
        return $this->hasOne(PollResults::className(), ['poll_id' => 'id'])->andOnCondition([
            'poll_results.user_id' => \Yii::$app->user->id
        ]);
    }

    public function getResultPercents() {

        $percents = [];
        $summary = 0;
        foreach ($this->answers as $index=>$answer) {
            $summary += $this->results[$index] ?: 0;
            $percents[$index] = 0;
        }

        foreach ($this->answers as $index=>$answer) {
            if ($this->results[$index]) {
                $percents[$index] = floor(($this->results[$index] / $summary) * 100);
            }
        }
        return $percents;

    }

    public function getCanEdit()
    {
        return \Yii::$app->user->can("admin");
    }

}
