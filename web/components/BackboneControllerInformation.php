<?php
namespace app\components;

use yii\base\Component;

class BackboneControllerInformation extends Component
{

    private $_data = [];
    public function __set($name, $value)
    {
        if (isset(\Yii::$app->controller->external) AND \Yii::$app->controller->external) {
            $this->_data['external'][\Yii::$app->controller->id][\Yii::$app->controller->action->id][$name] = $value;
        } else {
            $this->_data[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (isset(\Yii::$app->controller->external) AND \Yii::$app->controller->external) {
            return $this->_data['external'][\Yii::$app->controller->id][\Yii::$app->controller->action->id][$name];
        } else {
            return $this->_data[$name];
        }
    }

    public function toJSON()
    {
        return json_encode($this->_data);
    }

    public function toArray()
    {
        return $this->_data;
    }

    public function append($attr, $value)
    {
        $arr = $this->$attr;
        $arr[] = $value;
        $this->$attr = $arr;
    }

}