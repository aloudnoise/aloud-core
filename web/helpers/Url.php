<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 01.12.2017
 * Time: 15:01
 */

namespace app\helpers;


class Url extends \yii\helpers\Url
{

    public static function toRoute($route, $scheme = false)
    {

        if (!\Yii::$app->request->isConsoleRequest) {
            if ($route['from'] !== 0 AND \Yii::$app->request->get("from")) {
                $route['from'] = \Yii::$app->request->get("from");
            }

            if ($route['return'] !== 0 AND \Yii::$app->request->get("return")) {
                $route['return'] = \Yii::$app->request->get("return");
            }
        }

        if ($route['from'] === 0) {
            unset($route['from']);
        }

        if ($route['return'] === 0) {
            unset($route['return']);
        }

        return parent::toRoute($route, $scheme);
    }

}