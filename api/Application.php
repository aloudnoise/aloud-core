<?php
namespace aloud_core\api;


class Application extends \yii\web\Application
{

    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'languageDetector' => ['class' => 'aloud_core\api\components\LanguageDetector']
        ]);
    }

}