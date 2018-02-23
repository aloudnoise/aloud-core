<?php

namespace bilimal\web\models;

use common\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "bilimal_user".
 *
 * @property int $id
 * @property int $bilimal_user_id
 * $property int $user_type
 * @property int $user_id
 * @property string $ts
 * @property int $is_deleted
 * @property int $institution_id
 *
 */
class BilimalUser extends ActiveRecord
{

    const TYPE_NEW_BILIMAL = 1;
    const TYPE_OLD_BILIMAL = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bilimal_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bilimal_user_id', 'user_id'], 'required'],
            [['bilimal_user_id', 'user_id', 'user_type'], 'integer'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }


}
