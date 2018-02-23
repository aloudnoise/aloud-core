<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "support".
 *
 * @property int $id
 * @property int $organization_id
 * @property int $user_id
 * @property string $question
 * @property string $contacts
 * @property string $ts
 * @property string $info
 * @property int $is_deleted
 */
class Support extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'support';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['info'], 'safe'],
            [['question'], 'string', 'max' => 3000],
            [['contacts'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organization_id' => 'Organization ID',
            'user_id' => 'User ID',
            'question' => \Yii::t("main",'Вопрос'),
            'contacts' => \Yii::t("main",'Контакты'),
            'ts' => 'Ts',
            'info' => 'Info',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
