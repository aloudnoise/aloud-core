<?php

namespace aloud_core\web\widgets\EUploader;

use aloud_core\web\components\Widget;

class EUploader extends Widget
{

    public $assets_path = '@aloud_core/web/widgets/EUploader/assets';
    public $js = [
        'EUploader.js',
    ];

    public $css = [
        'EUploader.css'
    ];

    public $standalone = true;

    public function run()
    {

        return $this->render('setTemplates');

    }

}
?>