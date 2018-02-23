<?php
namespace app\helpers;

use common\models\Organizations;

class OrganizationUrl extends Url
{

    public static function to($url = '', $scheme = false)
    {

        $organization_id = Organizations::getCurrentOrganizationId();
        if (is_array($url) AND $organization_id) {
            $url['oid'] = $organization_id;
        }

        return parent::to($url, $scheme);
    }

}