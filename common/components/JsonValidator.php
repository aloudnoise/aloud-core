<?php
namespace aloud_core\common\components;

use yii\validators\Validator;

class JsonValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!empty($model->$attribute)) {
            if (!is_array($model->$attribute)) {
               $json = json_decode($model->$attribute, true);
                if (!is_array($json)) {
                    $this->addError($model, $attribute, 'Must be array to serialize');
                    return false;
                } else return true;
            }

            $json = json_encode($model->$attribute);

            if (!$json) {
                $this->addError($model, $attribute, 'Not valid json object');
                return false;
            }
        }
        return true;

    }
}