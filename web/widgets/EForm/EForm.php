<?php
namespace aloud_core\web\widgets\EForm;

use yii\helpers\Html;

class EForm extends \aloud_core\web\components\Widget
{

    protected $backbone = true;
    public $htmlOptions = [];

    public function init()
    {
        parent::init();
        echo Html::beginForm($this->htmlOptions['action'], $this->htmlOptions['method'], $this->htmlOptions);
    }

    public function run()
    {

        echo Html::endForm();

    }

}
?>