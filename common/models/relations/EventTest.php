<?php

namespace common\models\relations;

use common\models\Events;

class EventTest extends BaseTestRelation
{

    public static function tableName()
    {
        return "relations.event_test";
    }
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ["id"=>"target_id"]);
    }


}

?>