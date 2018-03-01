<?php
namespace aloud_core\api;


class Application extends \yii\web\Application
{
        
    public function bootstrap()
    {
        // TODO - Большой большой кастыль, пока не внесли эту фичу в КОР
        \Yii::$classMap['yii\base\ArrayableTrait'] = __DIR__.'/hooked/traits/ArrayableTrait.php';
        \Yii::$classMap['yii\helpers\ArrayHelper'] = __DIR__.'/hooked/helpers/ArrayHelper.php';

        parent::bootstrap();
    }

    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'languageDetector' => ['class' => 'aloud_core\api\components\LanguageDetector']
        ]);
    }

}