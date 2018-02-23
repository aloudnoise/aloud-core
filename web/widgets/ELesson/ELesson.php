<?php

namespace app\widgets\ELesson;

use app\models\CourseLessons;
use app\models\Courses;

class ELesson extends \app\components\Widget
{

    public $backbone = false;


    public $model = null;
    public $from = null;
    public $content = null;

    public function run()
    {

        if ($this->from) {
            $model = CourseLessons::find()->byOrganization()->byPk($this->from[1])->one();
            $this->model = $model;
            return $this->render("from");
        }

    }

}
?>