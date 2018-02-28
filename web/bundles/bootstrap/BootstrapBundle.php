<?php
namespace app\bundles\bootstrap;

use yii\web\AssetBundle;

/**
 * Class BootstrapBundle
 * @package app\bundles
 * @configuration http://getbootstrap.com/customize/?id=4a3a25795c9c6758361c
 */
class BootstrapBundle extends AssetBundle
{
    public $sourcePath = '@aloud_core/web/bundles/bootstrap/assets';
    public $css = [
        'css/tether.css',
        'css/bootstrap.css',
        'css/bootstrap-datepicker.css'
    ];
    public $js = [
        'js/popper.js',
        'js/bootstrap.js',
        'js/moment.js',
        'js/bootstrap-datepicker.js',
        'js/locales/bootstrap-datepicker.ru-RU.js',
        'js/locales/bootstrap-datepicker.kk-KZ.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'aloud_core\web\bundles\jquery\JQueryBundle',
    ];

    public static function registerTimePicker($view)
    {
        $bundle = self::register($view);
        $view->registerJsFile($bundle->baseUrl. '/js/bootstrap-timepicker.min.js', [
            'position' => $bundle->jsOptions['position'],
            'depends' => $bundle->depends
        ], 'timepicker');

        $view->registerCssFile(self::register($view)->baseUrl. '/css/bootstrap-timepicker.min.css', [
            'position' => $bundle->jsOptions['position'],
            'depends' => $bundle->depends
        ], 'timepicker-css');
    }
}