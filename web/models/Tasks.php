<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 08.08.2017
 * Time: 1:14
 */

namespace app\models;

use app\traits\BackboneRequestTrait;

class Tasks extends \common\models\Tasks
{
    use BackboneRequestTrait;

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('main', 'ID'),
            'name' => \Yii::t('main', 'Название'),
            'content' => \Yii::t("main",'Содержание'),
            'info' => \Yii::t('main', 'Info'),
            'ts' => \Yii::t('main', 'Ts'),
            'time' => \Yii::t("main","Время на прохождение")
        ];
    }

    public function deleteRequest($attributes = []) {
        $c = static::find()->byPk($attributes['id'])->one();
        if ($c->canEdit) {
            $c->delete();
            return true;
        }
        return false;
    }

}