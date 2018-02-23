<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 22.12.2017
 * Time: 16:03
 */

namespace bilimal\common\components;


class ActiveRecord extends \common\components\ActiveRecord
{

    public static function find()
    {
        return (new ActiveQuery(get_called_class()));
    }

}