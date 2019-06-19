<?php
namespace aloud_core\web\bundles\backbone;

use aloud_core\web\bundles\base\BaseBundle;
use aloud_core\web\components\View;
use yii\web\AssetBundle;

class BackboneBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/backbone/assets';
    public $css = [

    ];
    public $js = [
        'framework/underscore.js',
        'framework/backbone.js',
        'framework/backbone.syphon.js',
        'components/sync.js',
        'components/model.js',
        'components/collection.js',
        'components/component.js',
        'components/validation.js',
        'components/socket.js',
        'components/item.js',
        'components/module.js',
        'components/controller.js',
        'components/action.js',
        'components/widget.js'
    ];
    public $depends = [
        'aloud_core\web\bundles\jquery\JQueryBundle',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    
    public $publishOptions = [
        'forceCopy' => false
    ]

    public static function registerWidget($view, $name)
    {
        $bundle = self::register($view);
        $view->registerJsFile($bundle->baseUrl."/widgets/".$name.".js", [
                'depends' => [static::className()],
                'position'=> \aloud_core\web\components\View::POS_HEAD
        ]);
    }

}

?>
