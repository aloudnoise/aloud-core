<?php

namespace common\models\relations;

use common\models\Events;

class EventTask extends BaseTaskRelation
{

    public static function tableName()
    {
        return "relations.event_task";
    }
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ["id"=>"target_id"]);
    }


}

?>