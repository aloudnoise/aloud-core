<?php

namespace app\widgets\EMaterial;

class EMaterial extends \app\components\Widget
{

    public $number = null;
    public $backbone = true;
    public $type = null;
    public $model = null;
    public $link = array();
    public $htmlOptions = [];

    public $readonly = false;
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