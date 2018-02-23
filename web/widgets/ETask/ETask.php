<?php

namespace app\widgets\ETask;

class ETask extends \app\components\Widget
{

    public $backbone = false;
    public $type = "media";
    public $model = null;
    public $link = array();
    public $htmlOptions = [];
    public $readonly = false;
    public $from = null;
    public $canEdit = false;

    public function run()
    {

        if ($this->backbone) {
            return $this->render("index");
        } else {
            return $this->render($this->type);
        }

    }

}
?>