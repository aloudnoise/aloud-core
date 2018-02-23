<?php
namespace api\modules\tests\controllers;

use api\components\ActiveController;

class ImportController extends ActiveController
{
    public $modelClass = 'api\models\forms\QuestionsForm';
}