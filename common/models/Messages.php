<?php

namespace common\models;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $organization_id
 * @property int $chat_id
 * @property int $user_id
 * @property string $message
 * @property string $ts
 * @property string $info
 *
 * @property Chats $chat
 */
class Messages extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['chat_id'], 'integer'],
            [['message'], 'string', 'max' => 2000],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chats::className(), 'targetAttribute' => ['chat_id' => 'id']],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chats::className(), ['id' => 'chat_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

}
