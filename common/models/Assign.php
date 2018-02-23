<?php
namespace common\models;

use common\components\Model;

class Assign extends Model
{

    public $related_id = null;
    public $related_model = null;

    public $target_id = null;
    public $target_model = null;

    public static $relations = [
        'event_course' => 'common\models\relations\EventCourse',
        'event_group' => 'common\models\relations\EventGroup',
        'event_material' => 'common\models\relations\EventMaterial',
        'event_test' => 'common\models\relations\EventTest',
        'event_task' => 'common\models\relations\EventTask',
        'event_user' => 'common\models\relations\EventUser',
        'event_theme' => 'common\models\relations\EventTheme',
        'event_organization' => 'common\models\relations\EventOrganization',
        'group_user' => 'common\models\relations\GroupUser',
        'lesson_material' => 'common\models\relations\LessonMaterial',
        'lesson_task' => 'common\models\relations\LessonTask',
        'lesson_test' => 'common\models\relations\LessonTest',
        'test_question' => 'common\models\relations\TestQuestion',
        'test_theme' => 'common\models\relations\TestTheme',
        'dialog_user' => 'common\models\relations\DialogUser',
    ];


    public function rules()
    {
        return [
            [['related_id','target_id','related_model','target_model'], 'required'],
            [['related_id','target_id'], 'integer'],
            [['related_model', 'target_model'], 'string'],
            [['target_model'], function() {
                if (!$this->getRelationClass()) {
                    $this->addError("relation", "NO SUCH RELATION");
                    return false;
                }
                return true;
            }]
        ];
    }

    public function save()
    {

        if (!$this->validate()) return false;

        $relationModel = $this->relationClass;
        $relation = \Yii::createObject($relationModel);
        $relation->target_id = $this->target_id;
        $relation->related_id = $this->related_id;

        if (!$relation->save()) {
            $this->addErrors($relation->getErrors());
            return false;
        }

        return true;
    }

    public function getRelation()
    {

        return $this->target_model."_".$this->related_model;

    }

    public function getRelationClass()
    {
        $relation = $this->relation;
        if (!isset(static::$relations[$relation])) {
            return false;
        }
        return static::$relations[$relation];
    }

    public function getRelationModel()
    {
        $class = $this->relationClass;
        if ($class) {
            $model = $class::find()->byOrganization()->andWhere([
                'related_id' => $this->related_id,
                'target_id' => $this->target_id
            ])->one();
            if ($model) return $model;
        }
        return false;
    }

}