<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 03.01.2018
 * Time: 21:00
 */

namespace common\models\relations;

use common\models\Users;

/**
 * Class ChatUser
 * @package common\models\relations
 *
 * @property integer $role
 *
 */
class ChatUser extends RelationsTemplate
{

    const ROLE_MEMBER = 1;
    const ROLE_MODERATOR = 2;
    const ROLE_ADMIN = 3;

    public static function tableName()
    {
        return "relations.chat_user";
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'related_id']);
    }

}