<?php
namespace aloud_core\console;

class Application extends \yii\console\Application
{

    public function bootstrap()
    {
        \Yii::setAlias("@aloud_core", "@vendor/aloudnoise/aloud-core");
        parent::bootstrap();
    }


    public function coreCommands()
    {
        return array_merge(parent::coreCommands(), [
                'rabbit-listener' => 'aloud_core\console\controllers\RabbitListenerController',
        ]);
    }

}