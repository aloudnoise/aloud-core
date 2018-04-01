<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 02.04.2018
 * Time: 0:57
 */

namespace aloud_core\web\widgets\EJsonEditor;


use aloud_core\web\components\Widget;

class EJsonEditor extends Widget
{

    public $assets_path = '@aloud_core/web/widgets/EJsonEditor/assets';
    public $js = [
        'EJsonEditor.js'
    ];

    public $model = null;
    public $attribute = null;

    public function run()
    {

        return $this->render("index", [
            'model' => $this->model,
            'attribute' => $this->attribute
        ]);

    }


}