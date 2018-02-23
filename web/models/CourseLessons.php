<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 08.08.2017
 * Time: 1:37
 */

namespace app\models;

use app\traits\BackboneRequestTrait;
use Yii;

class CourseLessons extends \common\models\CourseLessons
{

    use BackboneRequestTrait;

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'name' => Yii::t('main', 'Название'),
            'content' => Yii::t('main', 'Содержание'),
            'info' => Yii::t('main', 'Info'),
            'ts' => Yii::t('main', 'Ts'),
            'user_id' => Yii::t('main', 'Создатель'),
            'course_id' => Yii::t('main', 'Курс'),
        ];
    }

    public function deleteAccess($attributes) {
        return true;
    }

    public function deleteRequest($attributes) {

        $m = self::find()->byPk($attributes['id'])->one();
        return $m->delete();

    }

    public function insertAccess($attributes) {
        return true;
    }

    public function insertRequest($attributes) {

        $material = Materials::find()->byPk($attributes['related_id'])->one();
        if ($material->type == 4) {

            $check = self::find()
                ->andWhere([
                    "course_id" => $attributes['target_id'],
                    "content" => $attributes['related_id']
                ])->one();

            $relation = new self();
            $relation->course_id = $attributes['target_id'];
            $relation->content = "$material->id";
            $relation->type = self::TYPE_DER;
            $relation->name = $material->name;

            if (!$check) {
                if ($relation->save()) {
                    return self::arrayAttributes($relation, [], [], true);
                }
            } else {
                return self::arrayAttributes($check, [], [], true);
            }
            $this->addErrors($relation->getErrors());
        }
        return false;

    }

}