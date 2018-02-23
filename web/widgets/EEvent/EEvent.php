<?php

namespace app\widgets\EEvent;

use app\models\Events;

class EEvent extends \app\components\Widget
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
            $this->model = Events::find()->byOrganization()->byPk($this->from[1])->one();
            return $this->render("from");
        }

        if ($this->backbone) {
            return $this->render("index");
        } else {
            return $this->render($this->type);
        }

    }

}
?>