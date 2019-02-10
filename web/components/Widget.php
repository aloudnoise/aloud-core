<?php

    namespace aloud_core\web\components;

    class Widget extends \yii\base\Widget
    {

        public $assets_path = null;
        public $js = [];
        public $css = [];

        protected $auto_start = false;

        const TYPE_STATIC = 0;
        const TYPE_TEMPLATE = 1;

        public $type = self::TYPE_STATIC;
        public $id = null;

        public function init()
        {
            $c = (new \ReflectionClass($this))->getShortName();

            if (!empty($this->assets_path)) {

                $bundle = \Yii::$app->assetManager->publish($this->assets_path);

                if (!empty($this->js)) {
                    foreach ($this->js as $js) {
                        $this->view->registerJsFile($bundle[1]."/".$js, [
                            View::POS_END
                        ]);
                    }
                }

                if (!empty($this->css)) {
                    foreach ($this->css as $css) {
                        $this->view->registerCssFile($bundle[1]."/".$css);
                    }
                }
            }

            if ($this->auto_start) {
                $widgets = \Yii::$app->data->widgets;
                $exists = [];
                if ($widgets) {
                    $exists = array_filter($widgets, function ($w) use ($c) {
                        return $c == $w;
                    });
                }
                if (empty($exists)) {
                    \Yii::$app->data->append("widgets", $c);
                }
            }

            if ($this->id == null) $this->id = $this->getId();
        }

    }
?>