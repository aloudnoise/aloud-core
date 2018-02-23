<?php
namespace app\models\results;

use app\traits\BackboneRequestTrait;

class TestResults extends \common\models\results\TestResults
{

    use BackboneRequestTrait;

    public function deleteAccess($attributes = []) {
        return \Yii::$app->user->can("base_teacher");
    }

    public function deleteRequest($attributes = []) {

        $m = self::find()->byPk($attributes['id'])->one();
        return $m->delete();

    }

    public function attributeLabels()
    {
        return [
            'status_note' => \Yii::t("main", "Причина")
        ];
    }


}