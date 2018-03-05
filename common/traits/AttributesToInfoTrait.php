<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 30.05.2017
 * Time: 20:00
 */

namespace aloud_core\common\traits;


trait AttributesToInfoTrait
{

    public function attributesToInfo()
    {
        return [];
    }

    private $_properties = -1;

    public function __get($name)
    {

        if (in_array($name, $this->attributesToInfo())) {
            return $this->infoJson[$name];
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->attributesToInfo())) {
            $this->setInfo($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }

}