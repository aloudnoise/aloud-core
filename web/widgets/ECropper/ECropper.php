<?php

namespace aloud_core\web\widgets\ECropper;

use aloud_core\web\components\Widget;

class ECropper extends Widget
{

    public $js = [
        '@app/widgets/ECropper/assets/ECropper.js'
    ];

    public function run()
    {
        return $this->render("crop_template");
    }
}
?>