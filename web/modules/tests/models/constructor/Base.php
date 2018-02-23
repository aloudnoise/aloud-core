<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 15.12.2017
 * Time: 15:30
 */

namespace app\modules\tests\models\constructor;


use common\behaviors\ThemesBehavior;
use common\components\Model;

class Base extends Model
{

    public $test = null;
    public $theme_id = null;

//    public function behaviors()
//    {
//        return array_merge(parent::behaviors(), [
//            'themesBehavior' => [
//                'class' => ThemesBehavior::className()
//            ]
//        ]);
//    }

    public function save()
    {
        return false;
    }

    public function getReturn($action = null)
    {
        return null;
    }

}