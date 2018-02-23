<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 05.12.2017
 * Time: 10:46
 */

namespace api\modules\tests\controllers;


use api\components\ActiveController;

class QuestionsController extends ActiveController
{

    public $modelClass = 'api\models\Questions';

}