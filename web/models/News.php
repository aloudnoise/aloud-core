<?php

namespace app\models;

use app\traits\BackboneRequestTrait;
use Yii;

class News extends \common\models\News
{
    use BackboneRequestTrait;

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'organization_id' => Yii::t('main', 'Organization ID'),
            'name' => Yii::t('main', 'Заголовок'),
            'image' => Yii::t('main', 'Фото'),
            'content' => Yii::t('main', 'Текст'),
            'type' => Yii::t('main', 'Тип'),
            'user_id' => Yii::t('main', 'Автор'),
            'is_deleted' => Yii::t('main', 'Is Deleted'),
            'ts' => Yii::t('main', 'Дата'),
            'info' => Yii::t('main', 'Info'),
            'tagsString' => Yii::t('main', 'Ключевые слова'),
        ];
    }
}