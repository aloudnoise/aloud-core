<?php
namespace app\models;

use app\traits\BackboneRequestTrait;

class Tests extends \common\models\Tests
{

    use BackboneRequestTrait;

    public function attributeLabels()
    {
        return [
            'name' => \Yii::t("main","Название"),
            'tagsString' => \Yii::t("main","Ключевые слова")
        ];
    }

}