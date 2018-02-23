<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 18.02.2018
 * Time: 22:17
 */

namespace common\behaviors;


use common\components\ActiveRecord;
use common\models\redis\Logs;
use yii\base\Behavior;
use yii\web\Controller;

class LogsBehavior extends Behavior
{

    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION => function() {
                $this->logControllerAction("visited");
            }
        ];
    }

    public function logModelAction($action)
    {
    }

}