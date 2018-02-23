<?php

namespace common\models;

use common\components\ActiveRecord;
use common\models\relations\TestQuestion;
use common\traits\AttributesToInfoTrait;
use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property integer $id
 * @property string $name
 * @property integer $ts
 * @property string $info
 * @property integer $user_id
 * @property integer $type
 * @property integer $theme_id
 * @property integer $weight
 */
class Questions extends \common\components\ActiveRecord
{

    use AttributesToInfoTrait;

    public function attributesToInfo()
    {
        return ['is_random'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
            [['user_id', 'type'], 'integer'],
            [['name'], 'filter', 'filter' => function($value) {
                return (string)$value;
            }],
            [['name'], 'string', 'max' => 5000],
            [['weight'], 'number'],
            [['is_random'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'name' => Yii::t('main', 'Name'),
            'ts' => Yii::t('main', 'Ts'),
            'info' => Yii::t('main', 'Info'),
            'user_id' => Yii::t('main', 'User ID'),
            'type' => Yii::t('main', 'Type'),
        ];
    }

    public function getTheme()
    {
        return $this->hasOne(Themes::className(), ["id" => "theme_id"]);
    }

    public function getAnswers()
    {
        return $this->hasMany(Answers::className(), ["question_id"=>"id"]);
    }

    public function getCorrect_count()
    {
        $ccount = count(array_filter($this->answers, function($a) {
           return $a->is_correct == 1;
        }));
        return $ccount;
    }



}
