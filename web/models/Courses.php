<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 08.08.2017
 * Time: 1:14
 */

namespace app\models;


use app\traits\BackboneRequestTrait;

class Courses extends \common\models\Courses
{

    use BackboneRequestTrait;

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('main', 'ID'),
            'name' => \Yii::t('main', 'Название'),
            'description' => \Yii::t("main",'Описание'),
            'continuous' => \Yii::t('main', 'Последовательный'),
            'tagsString' => \Yii::t('main', 'Ключевые слова'),
        ];
    }

}