<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 19.04.2017
 * Time: 15:37
 */

namespace aloud_core\common\components;


class DummyCache extends \yii\caching\DummyCache
{
    protected $is_caching = true;

    public function getIsCaching()
    {
        return $this->is_caching;
    }

    public function stop()
    {
        $this->is_caching = false;
    }

    public function start()
    {
        $this->is_caching = true;
    }
}