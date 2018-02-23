<?php

namespace common\models\relations;

use common\models\Events;
use common\models\Materials;

class EventMaterial extends RelationsTemplate
{

    public static function tableName()
    {
        return "relations.event_material";
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ["id"=>"target_id"]);
    }

    public function getMaterial()
    {
        return $this->hasOne(Materials::className(), ['id' => 'related_id']);
    }

}

?>