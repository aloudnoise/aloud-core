<?php

namespace app\models\relations;

class TestQuestion extends \common\models\relations\TestQuestion
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