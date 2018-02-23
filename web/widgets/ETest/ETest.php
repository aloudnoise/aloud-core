<?php

namespace app\widgets\ETest;

class ETest extends \app\components\Widget
{

    public $backbone = false;
    public $type = "media";
    public $model = null;
    public $htmlOptions = [];
    public $readonly = false;

    public $link = array();

    public $from = null;

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