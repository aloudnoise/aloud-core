<?php
namespace app\models\relations;

use app\traits\BackboneRequestTrait;

class LessonMaterial extends \common\models\relations\LessonMaterial
{

    public function deleteAccess($attributes = []) {
        return true;
    }

    public function deleteRequest($attributes = []) {

        $m = self::find()->byPk($attributes['id'])->one();
        return $m->delete();

    }

}