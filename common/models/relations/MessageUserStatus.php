<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 03.01.2018
 * Time: 21:01
 */

namespace common\models\relations;

/**
 * Class MessageUserStatus
 * @package common\models\relations
 *
 * @property integer $status
 *
 */
class MessageUserStatus extends RelationsTemplate
{

    const STATUS_NEW = 0;
    const STATUS_READ = 1;

    public static function tableName()
    {
        return "relations.message_user_status";
    }



}