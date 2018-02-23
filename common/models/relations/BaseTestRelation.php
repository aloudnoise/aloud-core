<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 06.12.2017
 * Time: 18:42
 */

namespace common\models\relations;


use common\models\Tests;
use common\traits\AttributesToInfoTrait;

class BaseTestRelation extends RelationsTemplate
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

    public function getTest()
    {
        return $this->hasOne(Tests::className(), ['id' => 'related_id']);
    }

    public function setStoredPassword($value)
    {
        \Yii::$app->session->set($this->id."_password", $value);
    }

    public function getStoredPassword()
    {
        return \Yii::$app->session->get($this->id."_password");
    }

}