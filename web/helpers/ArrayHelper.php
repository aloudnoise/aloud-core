<?php
namespace app\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{

    public static function map($array, $from, $to, $group = null)
    {
        if (!empty($array)) {
            return parent::map($array, $from, $to, $group);
        }
        return [];
    }

}