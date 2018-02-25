<?php

namespace aloud_core\web\bundles\jquery;

use yii\web\AssetBundle;

class JQueryBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/jquery/assets';
    public $css = [
        //'css/jquery-ui.min.css'
        'css/jquery-ui-1.12.1.css'
    ];
    public $js = [
        'js/jquery.js',
        'js/jquery-ui-1.12.1.js',
        'js/jquery.autocomplete.min.js',
        'js/jquery.sticky.js',
        'js/jquery.masked.js',
        //"https://code.jquery.com/jquery-migrate-3.0.1.js"
        'https://code.jquery.com/jquery-migrate-1.0.0.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}