<?php

namespace aloud_core\web\widgets\ENotifications;

use aloud_core\web\components\Widget;
use aloud_core\web\helpers\ArrayHelper;
use app\models\Events;
use common\models\Notifications;

class ENotifications extends Widget
{

    public $assets_path = '@aloud_core/web/widgets/ENotifications/assets';
    public $js = [
        'ENotifications.js'
    ];

    public $notifications = [];

    public function run()
    {

        return $this->render("templates", [
            'notifications' => $this->notifications
        ]);

    }

}
?>