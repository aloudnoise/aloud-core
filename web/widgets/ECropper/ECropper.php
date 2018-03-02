<?php

namespace aloud_core\web\widgets\ECropper;

use aloud_core\web\components\Widget;

class ECropper extends Widget
{
    public $assets_path = '@aloud_core/web/widgets/ECropper/assets';
    public $js = [
        'ECropper.js'
    ];

    public function run()
    {
        return $this->render("crop_template");
    }
}
?>