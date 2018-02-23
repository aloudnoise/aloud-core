<?php

namespace common\models;

use common\models\relations\ChatUser;

/**
 * This is the model class for table "chats".
 *
 * @property int $id
 * @property int $organization_id
 * @property string $name
 * @property int $type
 * @property int $user_id
 * @property string $ts
 * @property string $info
 *
 * @property Messages[] $messages
 */
class Chats extends \common\components\ActiveRecord
{

    const TYPE_DIALOG = 1;
    const TYPE_CHAT = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer', 'min' => self::TYPE_DIALOG, 'max' => self::TYPE_CHAT],
            [['name'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['chat_id' => 'id']);
    }

    public function getMembers()
    {
        return $this->hasMany(ChatUser::className(), ['target_id' => 'id'])->indexBy("related_id");
    }

    public function getMember()
    {
        $members = $this->members;
        return $members[\Yii::$app->user->id] ?: null;
    }

    public function getCompanion()
    {
        $members = $this->members;
        unset($members[\Yii::$app->user->id]);
        return array_values($members)[0] ?: null;
    }

}
