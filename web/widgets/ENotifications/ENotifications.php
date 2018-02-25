<?php

namespace aloud_core\web\widgets\ENotifications;

use aloud_core\web\components\Widget;

class ENotifications extends Widget
{
    public $backbone = true;

    public function run()
    {

        return $this->render("templates");

    }

}
?>