<?php
namespace api\controllers;

use api\components\ActiveController;

class TaskResultsController extends ActiveController
{

    public $modelClass = 'api\models\results\TaskResults';

    public function actions()
    {
        $actions = parent::actions();
        $actions[] = 'update';
        return $actions;
    }

}