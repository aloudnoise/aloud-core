<?php

namespace common\models\relations;

use common\components\ActiveRecord;
use common\models\Events;
use common\models\Organizations;
use common\models\Users;

class EventOrganization extends RelationsTemplate
{

    public static function tableName()
    {
        return "relations.event_organization";
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ["id"=>"target_id"]);
    }

    public function getOrganization()
    {
        return $this->hasOne(Organizations::className(), ['id' => 'related_id']);
    }

}

?>