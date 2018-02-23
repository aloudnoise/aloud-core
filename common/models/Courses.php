<?php

namespace common\models;

use common\traits\AttributesToInfoTrait;
use common\traits\CounterTrait;
use common\traits\TagsTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "courses".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $info
 * @property integer $state
 * @property integer $user_id
 * @property integer $ts
 * @property integer $continuous
 */
class Courses extends \common\components\ActiveRecord
{

    use UpdateInsteadOfDeleteTrait, CounterTrait, TagsTrait, AttributesToInfoTrait;

    const CREATED = 0;
    const SIGNED = 1;
    const PUBLISHED = 2;
    const REJECTED = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'courses';
    }

    public function attributesToInfo()
    {
        return ['dependence'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tagsString'], 'required'],
            [['info', 'tagsString'], 'string'],
            [['state', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 2000],
            [['continuous'], 'boolean'],
            [['dependence'], 'default', 'value' => 'id'],
        ];
    }

    public function getLessons()
    {
        return $this->hasMany(CourseLessons::className(), ["course_id"=>"id"]);
    }

    public function getLCount()
    {
        if (!\Yii::$app->cache->get("lcount_".$this->id)) {
            \Yii::$app->cache->set("lcount_".$this->id, $this->getLessons()->count(), 3600);
        }
        return \Yii::$app->cache->get("lcount_".$this->id);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->state = self::CREATED;
            $this->user_id = \Yii::$app->user->id;
        }

        return parent::beforeSave($insert);
    }

    public function isOwner()
    {
        return $this->user_id == \Yii::$app->user->id;
    }

    public function getCanEdit()
    {
        return $this->isOwner();
    }

    public function deleteAccess($attributes = [])
    {
        return true;
    }

    public function deleteRequest($attributes = []) {
        $c = Courses::find()->byPk($attributes['id'])->one();
        if ($c->canEdit) {
            $c->delete();
            return true;
        }
        return false;
    }

    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['related_id' => 'id']);
    }

    public function getShortDescription()
    {
        $s = $this->description;
        return (mb_strlen($s, "UTF-8")>200 ? mb_substr($s, 0, 197, "UTF-8")."..." : $s);
    }

    /**
     * Проверяет возможность прохождения конкретного урока внутри курса по своему полю 'dependence'
     *
     * @param $id
     * @return bool
     */
    public function canExecuteLesson($id)
    {
        if (!$this->continuous) {
            return true;
        }

        if ($this->dependence == 'id') {
            return $this->checkDependenceById($id);
        }
        // проверки по другим условиям
        return false;
    }

    /**
     * Дефолтная проверка по айдишникам уроков
     *
     * @param $id
     * @return bool
     */
    private function checkDependenceById($id)
    {
        $lessons = $this->getLessons()->andWhere(['<', 'id', $id])->all();
        foreach ($lessons as $lesson) {
            if (!$lesson->isCompleted()) {
                return false;
            }
        }
        return true;
    }
}
