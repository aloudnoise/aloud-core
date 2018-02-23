<?php
namespace app\models\forms;

use common\components\Model;

class GroupsModelForm extends Model
{

    public static $_models = [
        'regular' => 'app\models\Groups'
    ];

    public static function getModel()
    {
        return self::$_models['regular'];
    }

}