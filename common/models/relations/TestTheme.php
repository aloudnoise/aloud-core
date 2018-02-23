<?php

namespace common\models\relations;

use common\models\DicValues;
use common\models\Questions;
use common\models\Tests;
use common\models\Themes;

/**
 * Class TestTheme
 * @package common\models\relations
 *
 * @property integer $weight
 * @property integer $weight_type
 *
 */
class TestTheme extends RelationsTemplate
{

    const WEIGHT_TYPE_PERCENT = 1;
    const WEIGHT_TYPE_COUNT = 2;


    public static function tableName()
    {
        return "relations.test_theme";
    }

    public function getTest()
    {
        return $this->hasOne(Tests::className(), ["id"=>"target_id"]);
    }

    public function getTheme()
    {
        return $this->hasOne(Themes::className(), ["id"=>"related_id"]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['weight','weight_type'], 'required'],
            [['weight'], 'integer'],
            [['weight_type'], 'integer', 'min' => self::WEIGHT_TYPE_PERCENT, 'max' => self::WEIGHT_TYPE_COUNT],
        ]);
    }

}

?>