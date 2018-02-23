<?php

    namespace app\components;

    class Widget extends \yii\base\Widget
    {

        protected $backbone = false;
        protected $auto_start = false;

        const TYPE_STATIC = 0;
        const TYPE_TEMPLATE = 1;

        public $type = self::TYPE_STATIC;
        public $id = null;

        public function init()
        {
            $c = (new \ReflectionClass($this))->getShortName();
            if ($this->backbone)
            {
                (\Yii::$app->assetManager->getBundle("backbone"))::registerWidget($this->view, $c);
                if ($this->auto_start) {
                    \Yii::$app->data->append("widgets", $c);
                }
            }

            if ($this->id == null) $this->id = $this->getId();
        }

    }
?>