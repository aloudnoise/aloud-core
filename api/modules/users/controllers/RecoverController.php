<?php

namespace api\modules\users\controllers;

use api\components\ActiveController;

/**
 * Контроллер для востановления пароля
 * Class RecoverController
 * @package api\modules\users\controllers
 */
class RecoverController extends ActiveController
{
    public $modelClass = 'api\modules\users\models\Recover';

    public function actions()
    {
        return [
            'create',
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    public function behaviors()
    {
        $result = parent::behaviors();
        unset($result["authenticator"]);
        return $result;
    }

}

?>