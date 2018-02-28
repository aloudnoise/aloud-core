<?php

    namespace aloud_core\web\components;

    class Widget extends \yii\base\Widget
    {

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

            if (!empty($this->js)) {
                foreach ($this->js as $js) {
                    $this->view->registerJsFile($js, [
                        View::POS_END
                    ]);
                }
            }

            if (!empty($this->css)) {
                foreach ($this->css as $css) {
                    $this->view->registerCssFile($css);
                }
            }

            if ($this->auto_start) {
                \Yii::$app->data->append("widgets", $c);
            }

            if ($this->id == null) $this->id = $this->getId();
        }

    }
?>