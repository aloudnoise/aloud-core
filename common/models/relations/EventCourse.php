<?php

namespace common\models\relations;

use common\models\CourseLessons;
use common\models\Courses;
use common\models\Events;

class EventCourse extends RelationsTemplate
{

    public static function tableName()
    {
        return "relations.event_course";
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ["id"=>"target_id"]);
    }

    public function getCourse()
    {
        return $this->hasOne(Courses::className(), ['id' => 'related_id']);
    }

    public function getLessons()
    {
        return $this->hasMany(CourseLessons::className(), ['course_id' => 'related_id']);
    }

}

?>