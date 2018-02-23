<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 22.12.2017
 * Time: 16:03
 */

namespace bilimal\common\components;

class ActiveQuery extends \common\components\ActiveQuery
{

    public function byOrganization($organization_id = null)
    {
        return $this;
    }

}