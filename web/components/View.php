<?php
namespace aloud_core\web\components;

use aloud_core\web\bundles\base\BaseBundle;
use yii\helpers\Url;

class View extends \yii\web\View
{

    public function head()
    {
        return parent::head();
    }

    public function endBody()
    {
        parent::endBody();
    }

    public function setTitle($title, $breadcrumbs = true)
    {
        $this->title = $title;
        \Yii::$app->data->pageTitle = $title;
        if ($breadcrumbs) \Yii::$app->breadCrumbs->addLink($title);
    }

    public function addTitle($title, $breadcrumbs = true)
    {
        $this->title = $this->title." ".$title;
        \Yii::$app->data->pageTitle = $this->title;
        if ($breadcrumbs) \Yii::$app->breadCrumbs->addLink($title);
    }

    public function registerAssetBundle($name, $position = null)
    {
        if (!\Yii::$app->request->isAjax) {
            //run the parent method to parse the less/css file like Yii normally does.
            return parent::registerAssetBundle($name, $position);
        } else {

            /* @var $bundle \yii\web\AssetBundle */
            $bundle = parent::registerAssetBundle($name, $position);

            if (isset($bundle->static) AND $bundle->static == true) {
                return $bundle;
            }

            $url = $bundle->baseUrl."/";
            if ($bundle->css) {
                foreach ($bundle->css as $css) {
                    \Yii::$app->data->append("css",$url.$css);
                }
            }

            if ($bundle->js) {
                foreach ($bundle->js as $js) {
                    \Yii::$app->data->append("js",$url.$js);
                }
            }

            return $bundle;

        }
    }

    public function registerCssFile($url, $options = [], $key = null)
    {

        if (!\Yii::$app->request->isAjax) {
            //run the parent method to parse the less/css file like Yii normally does.
            return parent::registerCssFile($url, $options, $key);
        } else {

            \Yii::$app->data->append("css",$url);

        }
    }

    public function registerJsFile($url, $options = [], $key = null)
    {
        if (!\Yii::$app->request->isAjax) {
            return parent::registerJsFile($url, $options, $key);
        } else {
            \Yii::$app->data->append("js",$url);
        }
    }

    public function registerJs($js, $position = self::POS_READY, $key = null, $options = [])
    {

        if (!\Yii::$app->request->isAjax) {
            //run the parent method to parse the less/css file like Yii normally does.
            return parent::registerJs($js, $position, $key);
        } else {
            if (!$key) $key = uniqid();
            $s = array("id" => $key, "script" => $js);
            if (isset($options['force'])) {
                $s['force'] = true;
            }
            \Yii::$app->data->append("js", $s);

        }
    }

}
?>