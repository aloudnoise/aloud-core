<?php

namespace aloud_core\web\widgets\EInlineChoser;

class EInlineChoser extends \aloud_core\web\components\Widget
{

    public $htmlOptions = [];
    public $name = "";
    public $values = "";
    public $list = "";
    public $list_label_attribute = "nameOnForm";

    public function run()
    {

        if ($this->htmlOptions['class']) {
            $this->htmlOptions['class'].= " inline-choser";
        } else {
            $this->htmlOptions['class'] = "inline-choser";
        }

        if (!is_array($this->values)) {
            $this->values = json_decode($this->values, true);
        }

        return $this->render("index");

    }

}
?>