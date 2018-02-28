<?php

namespace aloud_core\web\widgets\EUploader;

use aloud_core\web\components\Widget;

class EUploader extends Widget
{

    public $js = [
        '@app/widgets/EUploader/assets/EUploader.js',
    ];

    public $css = [
        '@app/widgets/EUploader/assets/EUploader.css'
    ];

    public $standalone = true;

    public function run()
    {

        return $this->render('setTemplates');

    }

}
?>