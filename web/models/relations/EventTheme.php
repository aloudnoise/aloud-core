<?php
namespace app\models\relations;

class EventTheme extends \common\models\relations\EventTheme
{

    public function insertAcccss($attributes = [])
    {
        return \Yii::$app->user->can("base_teacher");
    }

    public function deleteAccess($attributes = []) {
        return \Yii::$app->user->can("base_teacher");
    }

    public function deleteRequest($attributes = []) {

        $m = self::find()->byPk($attributes['id'])->one();
        return $m->delete();

    }


}