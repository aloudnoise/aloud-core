<?php
namespace aloud_core\web;

use aloud_core\common\traits\ApplicationTrait;

class Application extends \yii\web\Application
{

    public function bootstrap()
    {
        \Yii::setAlias("@aloud_core", "@vendor/aloudnoise/aloud-core");
        parent::bootstrap();
    }

    public $aloud_core = [
        'socket_url' => null,
        'api_url' => null,
        'files_host' => null,
        'backbone_client_bundle' => null,
        'js_application_class' => 'BaseApplication'
    ];

    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'data' => ['class' => 'aloud_core\web\components\BackboneControllerInformation'],
            'response' => ['class' => 'aloud_core\web\components\Response'],
        ]);
    }

}