<?php
namespace aloud_core\web\widgets\EForm;

use yii\helpers\Html;

class EForm extends \aloud_core\web\components\Widget
{

    public $assets_path = '@aloud_core/web/widgets/EForm/assets';
    public $js = [
        'EForm.js'
    ];

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