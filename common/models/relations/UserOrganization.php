<?php

namespace common\models\relations;

use app\helpers\ArrayHelper;
use common\models\DicValues;
use common\models\Organizations;
use common\models\Users;
use common\traits\AttributesToInfoTrait;
use common\traits\TagsTrait;
use Yii;

/**
 * This is the model class for table "relations.user_organization".
 *
 * @property int $id
 * @property int $organization_id
 * @property int $related_id
 * @property int $target_id
 * @property string $info
 * @property string $ts
 * @property int $state
 * @property string $role
 */
class UserOrganization extends RelationsTemplate
{

    use AttributesToInfoTrait;

    public function attributesToInfo()
    {
        return array_values(ArrayHelper::map(DicValues::findByDic("pupil_custom_fields"), "id", "value"));
    }

    public function getCustomAttributes()
    {
        return $this->attributesToInfo();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relations.user_organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['role'], 'string', 'max' => 200],
            [$this->attributesToInfo(), 'safe']
        ]);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'related_id']);
    }

    public function getOrganization()
    {
        return $this->hasOne(Organizations::className(), ['id' => 'target_id']);
    }

    public function getFio()
    {
        return $this->user->fio;
    }

    public function getLogin()
    {
        return $this->user->login;
    }

    public function getEmailPhone()
    {
        return $this->user->email."<br />".$this->user->phone;
    }

    public function getFormattedTs()
    {
        return $this->getByFormat('ts', 'd.m.Y');
    }

}
