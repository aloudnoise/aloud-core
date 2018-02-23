<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 17.01.2018
 * Time: 15:01
 */

namespace common\models\relations;


use common\behaviors\ThemesBehavior;
use common\models\DicValues;
use common\models\Events;

class EventTheme extends RelationsTemplate
{

    public static function tableName()
    {
        return "relations.event_theme";
    }
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ["id"=>"target_id"]);
    }

    //фыв
    public function getTheme()
    {
        return DicValues::fromDic($this->related_id);
    }

}