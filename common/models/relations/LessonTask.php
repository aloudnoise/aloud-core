<?php

namespace common\models\relations;

use common\models\CourseLessons;
use common\models\Tasks;

class LessonTask extends BaseTaskRelation
{

    public static function tableName()
    {
        return "relations.lesson_task";
    }

    public function getLesson()
    {
        return $this->hasOne(CourseLessons::className(), ["id"=>"target_id"]);
    }
}

?>