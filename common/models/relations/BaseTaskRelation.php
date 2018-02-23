<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 06.12.2017
 * Time: 18:42
 */

namespace common\models\relations;


use common\models\Tasks;
use common\traits\AttributesToInfoTrait;

class BaseTaskRelation extends RelationsTemplate
{
    use AttributesToInfoTrait;

    public function attributesToInfo()
    {
        return ['password', 'criteria'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['password', 'criteria'], 'safe']
        ]);
    }

    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'related_id']);
    }

    public function setStoredPassword($value)
    {
        \Yii::$app->session->set($this->id."_task__password", $value);
    }

    public function getStoredPassword()
    {
        return \Yii::$app->session->get($this->id."_task__password");
    }

}