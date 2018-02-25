<?php

namespace aloud_core\web\widgets\EUploader;

use aloud_core\web\components\Widget;

class EUploader extends Widget
{
    public $backbone = true;

    public $standalone = true;

    public function run()
    {

        return $this->render('setTemplates');

    }

}
?>