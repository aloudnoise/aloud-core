<?php

namespace common\models;

use common\traits\AttributesToInfoTrait;
use Yii;

/**
 * This is the model class for table "example".
 *
 * @property int $id
 * @property string $name
 * @property string $info
 * @property int $organization_id
 * @property string $ts
 */
class Example extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'example';
    }

    // Если у таблицы есть поле info с типом данных jsonb. Для того, чтобы легко записать какието аттрибуты в это поле,
    // или достать эти аттрибуты
    use AttributesToInfoTrait; // подключаем этот трейт
    public function attributesToInfo() // Определяем этот метод и возвращаем список аттрибутов, которые будут хранится в info
    {
        return ['one_attr','two_attr','three_attr'];
    }
    // затем можно из этой модели напрямую доставать эти аттрибуты, $model->one_attr, $model->two_attr

    // чтобы эти аттрибуты стали доступны при массовом назначении $model->attributes = ['one_attr'=>'ads','two_attr','=>'xxx','three_attr'=>123],
    // нужно соответственно добавить их в rules
    public function rules()
    {
        return [
            [['name'],'required'],
            [['one_attr'], 'required'],
            [['name'], 'string', 'max' => 200],
            // Вот пример аттрибутов в info
            [['one_attr','two_attr'], 'string'],
            [['three_attr'], 'integer']
        ];
    }


}
