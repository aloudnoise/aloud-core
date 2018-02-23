<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 07.12.2017
 * Time: 12:14
 */

namespace bilimal\api\models;


class Assign extends \api\models\Assign
{

    public function getRelationClass()
    {
        static::$relations['event_group'] = 'bilimal\api\models\EventGroup';
        return parent::getRelationClass();
    }

}