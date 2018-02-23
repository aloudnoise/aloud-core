<?php
namespace api\modules\users\controllers;

use api\components\ActiveController;

class RegistrationController extends ActiveController
{
    public $modelClass = 'api\modules\users\models\Registration';

    public function behaviors()
    {
        $result = parent::behaviors();
        unset($result['authenticator']);
        return $result;
    }
}