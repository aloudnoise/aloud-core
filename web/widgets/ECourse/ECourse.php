<?php

namespace app\widgets\ECourse;

use app\models\CourseLessons;
use app\models\Courses;

class ECourse extends \app\components\Widget
{

    public $backbone = false;
    public $type = "media";
    public $model = null;
    public $htmlOptions = [];
    public $readonly = false;

    public $from = null;
    public $content = null;

    public function run()
    {

        if ($this->from) {
            $model = Courses::find()->byOrganization()->byPk($this->from[1])->one();
            if ($this->from[3]) {
                $lesson = CourseLessons::find()->byOrganization()->byPk($this->from[3])->one();
            }
            $this->model = $model;
            return $this->render("from", [
                'lesson' => $lesson ?: null
            ]);
        }

        if ($this->backbone) {
            return $this->render("index");
        } else {
            return $this->render($this->type);
        }

    }

}
?>