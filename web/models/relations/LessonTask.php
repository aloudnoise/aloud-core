<?php

namespace app\models\relations;

class LessonTask extends \common\models\relations\LessonTask
{

    public function deleteAccess($attributes = []) {
        return true;
    }

    public function deleteRequest($attributes = []) {

        $m = self::find()->byPk($attributes['id'])->one();
        return $m->delete();

    }

}

?>