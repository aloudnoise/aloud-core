<?php

namespace aloud_core\web\components;

use app\helpers\Url;
use yii\base\Component;
use yii\web\Cookie;

class LanguageSetter extends Component
{
    public $langs = [
        "ru-RU"=>1,
        "kk-KZ"=>2,
        "en-US"=>3,
    ];
    public function init()
    {        
        
        $lparam = \Yii::$app->urlManager->langParam;
        if(\Yii::$app->request->get($lparam) AND in_array(\Yii::$app->request->get($lparam), \Yii::$app->urlManager->languages)) {

            \Yii::$app->language = \Yii::$app->request->get($lparam);
            \Yii::$app->session->set($lparam, \Yii::$app->request->get($lparam));
            $get = \Yii::$app->request->get();
            unset($get[$lparam]);
            \Yii::$app->response->redirect(Url::to(\Yii::$app->controller->route, $get));
            
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
}
?>
