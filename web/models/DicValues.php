<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 07.05.2017
 * Time: 19:15
 */

namespace app\models;


use app\traits\BackboneRequestTrait;

class DicValues extends \common\models\DicValues
{

    use BackboneRequestTrait;

    public function formFields()
    {
        return [];
    }

}