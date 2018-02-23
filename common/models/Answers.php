<?php

namespace common\models;

use common\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "answers".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_correct
 * @property integer $ts
 * @property string $info
 * @property integer $question_id
 */
class Answers extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'question_id'], 'required'],
            [['is_correct'], 'filter', 'filter' => function($value) {
                return $value ? 1 : 0;
            }],
            [['is_correct', 'question_id'], 'integer'],
            [['name'], 'filter', 'filter' => function($value) {
                return (string)$value;
            }],
            [['name'], 'string', 'max' => 5000],
        ];
    }

}
