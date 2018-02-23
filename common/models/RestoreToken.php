<?php

namespace common\models;

use common\components\ActiveRecord;
use app\models\User;


/**
 * This is the model class for table "RestoreToken".
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property timestamp $ts
 * @property timestamp $expiration_ts
 * @property timestamp $used_ts
 * @property string $info
 *
 * @property User $user
 */
class RestoreToken extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'public."restore_token"';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['user_id', 'token'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
