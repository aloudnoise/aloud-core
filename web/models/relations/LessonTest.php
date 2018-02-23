<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 08.08.2017
 * Time: 15:35
 */

namespace app\models\relations;


class LessonTest extends \common\models\relations\LessonTest
{

    public function deleteAccess($attributes = []) {
        return true;
    }

    public function deleteRequest($attributes = []) {

        $m = self::find()->byPk($attributes['id'])->one();
        return $m->delete();

    }

}