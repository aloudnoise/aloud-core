<?php

namespace app\bundles\ckeditor_new;

use app\components\View;
use yii\web\AssetBundle;

class CKEditorBundle extends AssetBundle
{
    public $sourcePath = '@app/bundles/ckeditor_new/assets';
    public $js = [
        'ckeditor/ckeditor.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public static function register($view) {
        $view->registerJs("
           CKEDITOR_BASEPATH = '".\Yii::$app->assetManager->getBundle("ckeditor")->baseUrl."/ckeditor/';
           MATHJAX_LIB_PATH = '".\Yii::$app->assetManager->getBundle("ckeditor")->baseUrl."/mathjax/MathJax.js';
        ", View::POS_HEAD, 'ckeditor_config_path');
        $r = parent::register($view);
        $view->registerJsFile(\Yii::$app->assetManager->getBundle("ckeditor")->baseUrl."/ckeditor/ckeditor.js", \Yii::$app->assetManager->getBundle("ckeditor")->jsOptions);
        return $r;
    }

    public static function initiateUploader()
    {
        echo "<script type='text/template' id='ckeditor_upload_template'>
                    <div  class='file <%=data.error ? 'upload-error' : ''%> clearfix'>
                        <% if (!data.error && data.percent>0 && data.percent < 100) { %>
                        <div class='clearfix'>
                            <div style='margin-top:5px; margin-bottom:5px;' class=\"progress progress-striped\">
                                <div class=\"progress-bar progress-bar-info\" role=\"progressbar\" aria-valuenow=\"20\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: <%=data.percent%>%\">
                                    <span><%=(Math.ceil(data.loaded/1024)) + \"kb\" %>/<%=(Math.ceil(data.total/1024)) + \"kb\"%></span>
                                </div>
                            </div>
                        </div>
                        <% } %>
                    </div>
                </script>";
    }

}

?>
