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
        'js/common.js',
        'js/jquery.slimscroll.min.js'
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
        'aloud_core\web\bundles\vue\VueBundle'
    ];
    
    public $publishOptions = [
        'forceCopy' => false
    ];

}