<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 15.11.2017
 * Time: 14:05
 */

namespace app\models;


use app\traits\BackboneRequestTrait;

class Example extends \common\models\Example
{

    // Сдесь аттрибуты и методы которые будут использоваться только в веб версии
    public $onlyInWebProperty = null;
    public function onlyInWebMethod() {
        return 111;
    }

    // Чтобы можно было данную модель получить или записать из контроллера на Backbone, нужно определить трейт
    use BackboneRequestTrait;

    // Затем если модель инсертится в базу то
    public function insertAccess($attributes = [])
    {
        // Сдесь проверка на доступ
        return true;
    }
    public function insertRequest($attributes)
    {
        // в $attributes лежат аттрибуты переданные из модели в бекбон
        $model = new self();
        $model->attributes = $attributes;
        $model->save();
        return BackboneRequestTrait::arrayAttributes($model);
    }

    // Соответственно для айпдейта и удаления updateAccess, updateRequest, deleteAccess, deleteRequest

}