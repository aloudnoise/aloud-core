<?php
namespace app\bundles\backbone;

use yii\web\AssetBundle;

class BackboneBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@app/bundles/backbone/assets';
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
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'jquery'
    ];

    public static function registerWidget($view, $name)
    {
        $bundle = self::register($view);
        $view->registerJsFile($bundle->baseUrl."/widgets/".$name.".js", [
                'depends' => [self::className()],
                'position'=> \app\components\View::POS_HEAD
        ]);
    }

}

?>
