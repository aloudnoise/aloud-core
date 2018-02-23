<?php
namespace common\traits;


/**
 * Переводит json в объекты
 * 
 * Пример:
 *  $order = Order::findOne(['id' => 1]);
 *  $items = $order->modelsFromJson('goods', \common\models\OrderItem::className());
 *
 *  foreach ($items as $item) {
 *      print_r($item->attributes);
 *      print_r($item->product->attributes);
 *  }
 *
 * @author Govorov Andrey (thegovorovs@gmail.com)
 * @package common\traits
 */
trait JsonToModelTrait
{
    public function modelsFromJson($attributeName, $class) {
        return $this->modelsFromJsonByStr($this->$attributeName, $class);
    }

    public function modelsFromJsonByStr($stringJson, $class) {
        $items = json_decode($stringJson, true);
        if (!$items) {
            throw new \RuntimeException('WRONG_JSON', 400);
        }

        $objects = [];
        foreach ($items as $key => $item) {
            $object = new $class();
            $object->attributes = $item;
            if (!$object->validate()) {
                throw new \RuntimeException('WRONG_JSON_VALIDATION', 400);
            }

            $objects[$key] = $object;
        }

        return $objects;
    }

    /**
     * Конвертируем в JSON
     *
     * @param $attribute
     * @return bool|string
     */
    public function modelToJson($attribute) {
        $items = $this->$attribute;
        if (!$items) {
            return false;
        }

        $rows = [];
        foreach ($items as $key => $item) {
            if (is_array($item)) {
                $rows[$key] = $item;
            } else {
                $rows[$key] = $item->attributes;
            }
        }
        return json_encode($rows);
    }
}