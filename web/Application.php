<?php
namespace aloud_core\web;

class Application extends \yii\web\Application
{

    public function coreComponents()
    {
        array_merge(parent::coreComponents(), [
            'data' => ['class' => 'aloud_core\web\components\BackboneControllerInformation'],
            'response' => ['class' => 'aloud_core\web\components\Response'],
        ]);
    }

}