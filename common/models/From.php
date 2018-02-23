<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 06.12.2017
 * Time: 15:30
 */

namespace common\models;


use app\widgets\ECourse\ECourse;
use app\widgets\ELesson\ELesson;
use app\widgets\EEvent\EEvent;
use common\components\Model;

class From extends Model
{

    public $name = null;
    public $id = null;
    public $action = null;

    public $hash = null;

    public function __construct(array $config = [])
    {
        if (empty($config) AND !\Yii::$app->request->isConsoleRequest AND \Yii::$app->request->get("from")) {
            $this->name = \Yii::$app->request->get("from")[0];
            $this->id = \Yii::$app->request->get("from")[1];
            $this->action = \Yii::$app->request->get("from")[2];
            $this->hash = \Yii::$app->request->get("from")[3];
        } else {
            $this->name = $config[0];
            $this->id = $config[1];
            $this->action = $config[2];
            $this->generateHash();
        }
    }

    public function getParams()
    {
        return [
            $this->name,
            $this->id,
            $this->action,
            $this->hash
        ];
    }

    public function generateHash()
    {
        $this->hash = $this->hashIt();
    }

    public function hashIt()
    {
        return md5($this->id.$this->name.$this->action.\Yii::$app->params['secret_word']);
    }

    private static function _getList()
    {
        $list = [
            'event' => [
                'model' => Events::className(),
                'widget' => EEvent::className()
            ],
            'course' => [
                'model' => Courses::className(),
                'widget' => ECourse::className()
            ],
            'lesson' => [
                'model' => CourseLessons::className(),
                'widget' => ELesson::className()
            ]
        ];
        return $list;
    }

    public function rules()
    {
        return [
            [['id','name','action','hash'], 'required'],
            [['hash'], function() {
                if ($this->hash != $this->hashIt()) {
                    $this->addError("hash", "BAD HASH");
                    return false;
                }
            }]
        ];
    }

    public function getModelClass()
    {
        if (!$this->validate()) {
            return false;
        }
        return self::_getList()[$this->name]['model'];
    }

    public function getWidgetClass()
    {
        if (!$this->validate()) {
            return false;
        }
        return self::_getList()[$this->name]['widget'];
    }

    public function getAssignedRelationClass($model_name)
    {
        $assign = new Assign();
        $assign->target_model = $this->name;
        $assign->related_model = $model_name;
        return $assign->relationClass;
    }

    /**
     * Возвращает линковочную связь на основании переданных данных и данных текущей модели From
     *
     * @param $model_id
     * @param $model_name
     * @return mixed
     */
    public function getAssignedRelation($model_id, $model_name)
    {
        $assign = new Assign();
        $assign->target_model = $this->name;
        $assign->target_id = $this->id;
        $assign->related_model = $model_name;
        $assign->related_id = $model_id;
        return $assign->relationModel;
    }
}