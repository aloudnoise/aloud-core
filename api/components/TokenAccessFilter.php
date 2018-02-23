<?php

namespace api\components;

use yii\base\ActionFilter;
use yii\web\MethodNotAllowedHttpException;

class TokenAccessFilter extends ActionFilter
{
    public function beforeAction($action)
    {

        $token = \Yii::$app->request->getHeaders()->get("X-TOKEN");

        $data = \Yii::$app->request->getBodyParams();

        $_token = "";
        foreach ($data as $k) {
            $_token .= !is_array($k) ? $k : json_encode($k);
        }
        $_token .= \Yii::$app->params['secret_word'];
        $_token = md5($_token);

        if ($_token !== $token) {
            throw new MethodNotAllowedHttpException("WRONG TOKEN");
        }

        return parent::beforeAction($action);
    }

}