<?php

namespace common\models\relations;

use common\models\results\TestResults;
use common\models\Tests;

class LessonTest extends BaseTestRelation
{

    public static function tableName()
    {
        return "relations.lesson_test";
    }

}

?>