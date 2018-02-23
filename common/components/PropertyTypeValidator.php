<?php
namespace common\components;

use common\models\PropertyType;
use yii\validators\Validator;

/**
 * Проверяем тип аттрибута. Все возможные аттрибуты описаны в класса common\models\PropertyType
 *
 * @package common\components
 */
class PropertyTypeValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!empty($model->$attribute)) {
            $availableTypes = PropertyType::getLabels();

            if (!$availableTypes[$model->$attribute]) {
                $this->addError($model, $attribute, 'Wrong product type');
                return false;
            }
        }
        return true;
    }
}