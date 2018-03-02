<?php

namespace aloud_core\web\widgets\ENotifications;

use aloud_core\web\components\Widget;

class ENotifications extends Widget
{

    public $assets_path = '@aloud_core/web/widgets/ENotifications/assets';
    public $js = [
        'ENotifications.js'
    ];

    public function run()
    {

        return $this->render("templates");

    }

}
?>