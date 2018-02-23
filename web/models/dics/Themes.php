<?php
namespace app\models\dics;

use app\traits\BackboneRequestTrait;

class Themes extends \common\models\Themes
{

    use BackboneRequestTrait;

    public function formFields()
    {
        return [
        ];
    }

}