<?php

namespace aloud_core\web\widgets\ECropper;

use aloud_core\web\components\Widget;

class ECropper extends Widget
{
    public $backbone = true;

    public function run()
    {
        return $this->render("crop_template");
    }
}
?>