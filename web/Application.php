<?php
namespace aloud_core\web;

use aloud_core\common\traits\ApplicationTrait;

class Application extends \yii\web\Application
{

    use ApplicationTrait;

    public function coreComponents()
    {
        array_merge(parent::coreComponents(), [
            'data' => ['aloud_core\web\components\BackboneControllerInformation'],
            'response' => ['class' => 'aloud_core\web\components\Response'],
        ]);
    }

}