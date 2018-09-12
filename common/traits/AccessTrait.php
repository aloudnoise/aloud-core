<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 12.09.2018
 * Time: 15:00
 */

namespace aloud_core\common\traits;


trait AccessTrait
{

    public function getCanAdd()
    {
        return true;
    }

    public function getCanEdit()
    {
        return true;
    }

    public function getCanDelete()
    {
        return true;
    }

}