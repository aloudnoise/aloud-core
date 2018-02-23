<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 16.02.2018
 * Time: 17:37
 */

namespace common\models\notifications;


class TaskNotification extends BaseNotification
{

    public static function getType()
    {
        return static::TYPE_TASK;
    }

}