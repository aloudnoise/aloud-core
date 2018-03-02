<?php

namespace aloud_core\web\bundles\base;

use aloud_core\web\bundles\backbone\BackboneBundle;
use aloud_core\web\bundles\jquery\JQueryBundle;
use aloud_core\web\components\View;
use yii\web\AssetBundle;

class BaseBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/base/assets';
    public $js = [
        'js/base.js',
        'js/common.js'
    ];
    public $css = [
        'css/base.css'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'aloud_core\web\bundles\jquery\JQueryBundle',
        'aloud_core\web\bundles\bootstrap\BootstrapBundle',
        'aloud_core\web\bundles\urlmanager\UrlManagerBundle',
        'aloud_core\web\bundles\backbone\BackboneBundle',
        'aloud_core\web\bundles\jstrans\JSTransBundle',
        'aloud_core\web\bundles\font_awesome\FontAwesomeBundle',
    ];

    public static function registerConstants($view)
    {
        $view->registerJs("// Some php vars
                window.CORE = {};
                CORE.BACKBONE_ASSETS = '".BackboneBundle::register($view)->baseUrl."';
                CORE.BACKBONE_CLIENT_ASSETS = '".(\Yii::$app->aloud_core['backbone_client_bundle'])::register(\Yii::$app->view)->baseUrl."';
                CORE.BASE_ASSETS = '".BaseBundle::register(\Yii::$app->view)->baseUrl."';
                CORE.DEBUG = ".intval(YII_DEBUG).";
                CORE.URL_ROOT = '';
                CORE.SOCKET_URL = '".\Yii::$app->aloud_core['socket_url']."';
                CORE.API_URL = '".\Yii::$app->aloud_core['api_url']."';
                CORE.FILES_HOST = '".\Yii::$app->aloud_core['files_host']."';
                CORE.TRACKING_CODE = false;
            ", View::POS_HEAD, 'constants');
    }

}