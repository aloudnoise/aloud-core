<?php

namespace aloud_core\web\components;

use app\helpers\Url;
use yii\base\Component;
use yii\web\Cookie;

class LanguageSetter extends Component
{

    public $aliases = [
        null => null,
        'ru' => 'ru-RU',
        'kz' => 'kk-KZ',
        'en' => 'en-US',
    ];

    public $langs = [
        "ru-RU"=>1,
        "kk-KZ"=>2,
        "en-US"=>3,
    ];
    public function init()
    {

        $lparam = \Yii::$app->urlManager->langParam;
        $language = $this->aliases[\Yii::$app->request->get($lparam)];

        if(\Yii::$app->request->get($lparam) AND in_array($language, \Yii::$app->urlManager->languages)) {
            $this->setLanguage($language);
        }
        else if (\Yii::$app->session->get($lparam) AND in_array(\Yii::$app->session->get($lparam),\Yii::$app->urlManager->languages))
            \Yii::$app->language = \Yii::$app->session->get($lparam);
        else if(isset(\Yii::$app->request->cookies[$lparam]) AND in_array(\Yii::$app->request->cookies[$lparam],\Yii::$app->urlManager->languages))
            \Yii::$app->language = \Yii::$app->request->cookies[$lparam]->value;
        
    }
    public function getLN($lang = null)
    {
        if ($lang == null) $lang = \Yii::$app->language;
        return $this->langs[$lang];
    }

    public function setLanguage($language)
    {
        $lparam = \Yii::$app->urlManager->langParam;
        \Yii::$app->language = $language;
        \Yii::$app->session->set($lparam, $language);
    }

}
?>
