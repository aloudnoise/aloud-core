<?php

namespace common\behaviors;

use common\components\ActiveRecord;
use yii\base\Behavior;

class DynamicSelectBehavior extends Behavior
{

    public $select_attributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_VALIDATE => 'saveAttributes',
        ];
    }

    protected $_attributes = [];

    public function canSetProperty($name, $checkVars = true)
    {
        if (isset($this->select_attributes[$name])) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    public function __set($name, $value)
    {
        if (isset($this->select_attributes[$name])) {
            $this->_attributes[$name] = $value;
        } else {
            $this->owner->__set($name, $value);
        }
    }

    public function saveAttributes()
    {
        foreach ($this->select_attributes as $attr => $s_a) {
            if (isset($this->_attributes[$attr])) {
                $explode = explode("#", $this->_attributes[$attr]);
                $is_new = false;
                $value = $this->_attributes[$attr];
                if ($explode[0] AND $explode[0] == "add") {
                    $is_new = true;
                    $value = $explode[1];
                }
                if ($is_new) {
                    $model = $s_a['instance']($value);
                    $model->save();
                    $value = $model->id;
                }
                $this->owner->{$s_a['target_attribute']} = $value;
            } else {
                $this->owner->{$s_a['target_attribute']} = null;
            }
        }
    }

}