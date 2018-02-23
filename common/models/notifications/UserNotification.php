<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 18.02.2018
 * Time: 1:03
 */

namespace common\models\notifications;
use app\helpers\OrganizationUrl;
use app\helpers\Url;
use common\models\Events;
use common\models\Organizations;
use common\models\redis\OnlineUsers;
use common\models\relations\EventUser;
use common\models\Users;

/**

 *
 * Class UserNotification
 * @package common\models\notifications
 */
class UserNotification extends BaseNotification
{

    public function rules()
    {
        return array_merge([
        ], parent::rules());
    }

    public static function getType()
    {
        return static::TYPE_USER;
    }

    public function save($runValidation = true, $attributeNames = null)
    {

        if ($this->channel(static::CHANNEL_SOCKET)) {
            $this->beforeSave(true);

            $this->socket([
                new OnlineUsers([
                    'user_id' => $this->target_id
                ])
            ]);

            return true;
        }

        if ($this->channel(static::CHANNEL_WEB)) {
            return parent::save($runValidation, $attributeNames);
        }
    }

}