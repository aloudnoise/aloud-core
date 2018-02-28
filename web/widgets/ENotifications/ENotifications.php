<?php

namespace aloud_core\web\widgets\ENotifications;

use aloud_core\web\components\Widget;

class ENotifications extends Widget
{

    public $js = [
        '@app/widgets/ENotifications/assets/ENotifications.js'
    ];

    public function run()
    {

        return $this->render("templates");

    }

}
?>