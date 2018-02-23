<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 19.04.2017
 * Time: 15:37
 */

namespace common\components;


class DummyCache extends \yii\caching\DummyCache
{
    protected $is_caching = true;

    public function getIsCaching()
    {
        return $this->is_caching;
    }

    public function pause()
    {
        $this->is_caching = false;
    }

    public function resume()
    {
        $this->is_caching = true;
    }
}