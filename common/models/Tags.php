<?php

namespace common\models;

use common\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property string $name
 * @property integer $record_id
 * @property string $table
 * @property integer $ts
 * @property string $info
 */
class Tags extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'record_id', 'table'], 'required'],
            [['record_id'], 'integer'],
            [['info'], 'string'],
            [['name', 'table'], 'string', 'max' => 255],
        ];
    }

    public static function autoComplete($attribute, $query)
    {
        $data = static::find()->filterWhere(["like", $attribute, $query])
            ->distinct(true)->all();

        $result = [
            "query"=>$query,
            "suggestions"=>[]
        ];
        if (!empty($data)) {
            foreach($data as $d) {
                $result['suggestions'][] = $d->{$attribute};
            }
        }
        return $result;
    }

}
