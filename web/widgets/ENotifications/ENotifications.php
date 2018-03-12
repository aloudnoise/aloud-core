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

    public function run()
    {

        $events = Events::getCurrentEvents();

        $notifications = Notifications::find()
        ->joinWith(['myNotificationStatus'])
        ->andWhere([
            'LIKE', 'channels', '___1_'
        ])
        ->andWhere([
            'OR',
            ['notifications.target_id' => \Yii::$app->user->id, 'target_type' => Notifications::TARGET_USER],
            ['AND', [
                'in', 'notifications.target_id', ArrayHelper::map($events, 'id','id'),
                'target_type' => Notifications::TARGET_EVENT
            ]],
            ['target_type' => Notifications::TARGET_ALL],
            ['target_type' => \Yii::$app->user->can("teacher") ? Notifications::TARGET_TEACHERS : Notifications::TARGET_PUPILS]
        ])
            ->andWhere([
                'IS', 'notification_user_status.id', null
            ])
            ->orderBy('notifications.ts DESC')
            ->all();

        return $this->render("templates", [
            'notifications' => $notifications
        ]);

    }

}
?>