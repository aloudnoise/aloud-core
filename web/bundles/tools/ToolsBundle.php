<?php

namespace aloud_core\web\bundles\tools;

use aloud_core\web\components\View;
use yii\web\AssetBundle;

class ToolsBundle extends AssetBundle
{
    public $static = true;
    public $sourcePath = '@aloud_core/web/bundles/tools/assets';
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'aloud_core\web\bundles\jquery\JQueryBundle',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    /**
     * @param View $view
     */
    public static function registerJgrowl($view)
    {
        $bundle = static::register($view);
        $view->registerJsFile($bundle->baseUrl."/jgrowl/jquery.jgrowl.min.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jgrowl");
        $view->registerCssFile(self::register($view)->baseUrl."/jgrowl/jquery.jgrowl.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jgrowl_css");
    }

    public $publishOptions = [
        'forceCopy' => false
    ]
    
    /**
     * @param View $view
     */
    public static function registerJCrop($view)
    {
        $bundle = static::register($view);

        $view->registerJsFile($bundle->baseUrl."/jcrop/jquery.Jcrop.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jcrop");
        $view->registerCssFile($bundle->baseUrl."/jcrop/jquery.Jcrop.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jcrop_css");
    }

    /**
     * @param View $view
     */
    public static function registerChosen($view)
    {

        $bundle = static::register($view);

        $view->registerJsFile($bundle->baseUrl."/chosen/chosen.jquery.min.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "chosen");
        $view->registerJsFile($bundle->baseUrl."/chosen/chosen_with_add.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "dynamic_chosen");
        $view->registerCssFile($bundle->baseUrl."/chosen/chosen.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "chosen_css");
    }

    /**
     * @param View $view
     */
    public static function registerRange($view)
    {

        $bundle = static::register($view);

        $view->registerJsFile($bundle->baseUrl."/range/js/ion.rangeSlider.min.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "range");
        $view->registerCssFile($bundle->baseUrl."/range/css/ion.rangeSlider.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "range_css");
        $view->registerCssFile($bundle->baseUrl."/range/css/ion.rangeSlider.skinHTML5.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "range_theme");
    }

    /**
     * @param View $view
     */
    public static function registerInlineChoser($view)
    {

        $bundle = static::register($view);

        $view->registerJsFile($bundle->baseUrl."/inline-choser/choser.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "choser");
        $view->registerCssFile($bundle->baseUrl."/inline-choser/choser.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "choser_css");
    }


    public static function registerTool($view, $name) {
        $bundle = static::register($view);

        if (file_exists($bundle->basePath."/$name/js/$name.js")) {
            $view->registerJsFile($bundle->baseUrl . "/$name/js/$name.js", [
                'position' => $bundle->jsOptions['position'],
                "depends" => $bundle->depends
            ], "$name");
        }
        if (file_exists($bundle->basePath."/$name/css/$name.css")) {
            $view->registerCssFile($bundle->baseUrl . "/$name/css/$name.css", [
                'position' => $bundle->jsOptions['position'],
                "depends" => $bundle->depends
            ], "$name" . "_css");
        }
    }

}

?>
