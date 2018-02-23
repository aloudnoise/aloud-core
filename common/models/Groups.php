<?php

namespace common\models;

use common\models\relations\GroupUser;
use common\traits\AttributesToInfoTrait;
use common\traits\DateFormatTrait;
use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name
 * @property string $ts
 * @property int $is_deleted
 * @property string $info
 * @property integer $user_id
 */
class Groups extends \common\components\ActiveRecord
{

    use DateFormatTrait, AttributesToInfoTrait;

    public function attributesToInfo()
    {
        return ['description'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['info'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000]
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(GroupUser::className(), ['target_id' => 'id']);
    }

    public function getOwner()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getCanEdit()
    {
        return ($this->user_id == \Yii::$app->user->id OR \Yii::$app->user->can("specialist"));
    }

}
