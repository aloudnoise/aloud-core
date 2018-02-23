<?php

namespace common\models\relations;

use common\models\Groups;
use common\models\Users;
use Yii;

/**
 * This is the model class for table "relations.group_user".
 *
 * @property int $id
 * @property int $organization_id
 * @property int $related_id
 * @property int $target_id
 * @property string $info
 * @property string $ts
 * @property int $state
 * @property int $is_deleted
 * @property string $group_role
 */
class GroupUser extends RelationsTemplate
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relations.group_user';
    }

    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'target_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'related_id']);
    }

}
