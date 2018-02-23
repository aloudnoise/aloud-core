<?php

namespace common\models\relations;

use common\models\Materials;

class LessonMaterial extends RelationsTemplate
{

    public static function tableName()
    {
        return "relations.lesson_material";
    }

    public function getMaterial()
    {
        return $this->hasOne(Materials::className(), ["id"=>"related_id"]);
    }



}

?>