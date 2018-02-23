<?php

namespace common\models;

use common\components\PhoneNumberValidator;
use Yii;

/**
 * This is the model class for table "auth_credentials".
 *
 * @property string $credential
 * @property string $validation
 * @property string $type
 * @property int $user_id
 * @property string $ts
 */
class AuthCredentials extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_credentials';
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'registration' => ['credential','validation','type','user_id'],
            'change_password' => ['validation']
        ]);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credential', 'validation', 'type', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['credential'], 'string', 'max' => 255],
            [['credential'], PhoneNumberValidator::className(), 'when' => function($model) {
                return $model->type == 'phone';
            }],
            [['credential'], 'email', 'when' => function($model) {
                return $model->type == 'email';
            }],
            [['validation'], 'string', 'max' => 1000],
            [['validation'], 'filter', 'on' => ['registration'], 'filter' => function($value) {
                return ($value != "NOT_REGISTERED" AND $this->isNewRecord) ? \Yii::$app->security->generatePasswordHash($value) : $value;
            }],
            [['validation'], 'filter', 'on' => ['change_password'], 'filter' => function($value) {
                return \Yii::$app->security->generatePasswordHash($value);
            }],
            [['type'], 'string', 'max' => 25],
            [['credential'], 'filter', 'filter' => function($value) {
               return mb_strtolower($value, 'UTF-8');
            }],
            [['credential'], 'unique'],
        ];
    }
}
