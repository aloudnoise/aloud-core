<?php

namespace common\models;

use common\components\ActiveRecord;
use common\models\relations\LessonMaterial;
use common\models\relations\LessonTask;
use common\models\relations\LessonTest;
use common\traits\UpdateInsteadOfDeleteTrait;

/**
 * This is the model class for table "course_lessons".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property string $info
 * @property integer $ts
 * @property integer $user_id
 * @property integer $course_id
 * @property integer $type
 */
class CourseLessons extends ActiveRecord
{

    use UpdateInsteadOfDeleteTrait;

    const TYPE_INNER = 1;
    const TYPE_DER = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_lessons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content', 'course_id'], 'required'],
            [['content', 'info'], 'string'],
            [['user_id', 'course_id', 'type'], 'integer'],
            [['name'], 'string', 'max' => 1000],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {

        if ($insert AND \Yii::$app->cache->exists("lcount_".$this->course_id)) {
            \Yii::$app->cache->delete("lcount_".$this->course_id);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {

        if (\Yii::$app->cache->exists("lcount_".$this->course_id)) {
            \Yii::$app->cache->delete("lcount_".$this->course_id);
        }

        return parent::afterDelete();
    }

    public function getCourse()
    {
        return $this->hasOne(Courses::className(), ['id' => 'course_id']);
    }

    public function getMaterials()
    {
        return $this->hasMany(LessonMaterial::className(), ["target_id" => "id"]);
    }

    public function getTasks()
    {
        return $this->hasMany(LessonTask::className(), ["target_id" => "id"])->notDeleted();
    }

    public function getTests()
    {
        return $this->hasMany(LessonTest::className(), ["target_id" => "id"])->notDeleted();
    }

    /**
     * Возвращает true, если все задания и тесты, привязвнные к уроку, выполнены
     * Если нет заданий и тестов, считается, что урок выполнен
     * @return bool
     */
    public function isCompleted()
    {
        $from = new From(['lesson', $this->id]);
        foreach ($this->tests as $lesson_test) {
            if (!$lesson_test->test->getMyResult($from)) return false;
        }
        foreach ($this->tasks as $lesson_task) {
            if (!$lesson_task->task->getMyResult($from)) return false;
        }
        return true;
    }

    /**
     * Возвращает true, если урок доступен для прохождения
     *
     * @return bool
     */
    public function canExecute()
    {
        return $this->course->canExecuteLesson($this->id);
    }
}
