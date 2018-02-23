<?php

namespace common\models;

use common\models\results\TaskResults;
use common\traits\AttributesToInfoTrait;
use common\traits\TagsTrait;
use common\traits\UpdateInsteadOfDeleteTrait;

/**
 * This is the model class for table "tasks".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property integer $ts
 * @property string $info
 * @property integer $time
 * @property integer $is_shared
 */
class Tasks extends \common\components\ActiveRecord
{
    use UpdateInsteadOfDeleteTrait, TagsTrait, AttributesToInfoTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public function attributesToInfo()
    {
        return ['files'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content', 'time'], 'required'],
            [['tagsString'], 'string'],
            [['time'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['content'], 'string', 'max' => 2000],
            [['is_shared'], 'safe'],
            [['is_shared'], 'filter', 'filter' => function($value) {
                return $value ? 1 : 0;
            }],
            [['files'], 'safe'],
            [['files'], 'filter', 'filter' => function($value) {
                if (!empty($value)) {
                    foreach ($value as $key => $v) {
                        $value[$key] = is_array($v) ? $v : json_decode($v, true);
                    }
                }
                return $value;
            }]
        ];
    }

    public function getShortName()
    {
        $s = $this->name;
        return (mb_strlen($s, "UTF-8")>50 ? mb_substr($s, 0, 47, "UTF-8")."..." : $s);
    }

    public function getShortContent()
    {
        $s = $this->content;
        return (mb_strlen($s, "UTF-8")>70 ? mb_substr($s, 0, 67, "UTF-8")."..." : $s);
    }

    public function getResults()
    {
        return $this->hasMany(TaskResults::className(), ['task_id' => 'id']);
    }

    /**
     * Возвращает модель TaskResults -
     * результат выполнения задания, привязанного к определенному уроку, текущего пользвателя
     *
     * @param From $from
     * @return array|\yii\db\ActiveRecord
     */
    public function getMyResult($from)
    {
        return $this->getResults()
            ->byUser()
            ->andWhere([
                'from' => $from->name,
                'from_id' => $from->id,
            ])
            ->one();
    }

    /**
     * Возвращает список ответов на текущее задание, прикрепленное к уроку
     *
     * @param From $from
     * @return \yii\db\ActiveQuery
     */
    public function getResultsByLesson($from)
    {
        return $this->getResults()
            ->andWhere([
                'from' => $from->name,
                'from_id' => $from->id,
            ])
            ->all();
    }

    public function getCanEdit()
    {
        return $this->user_id == \Yii::$app->user->id;
    }

}
