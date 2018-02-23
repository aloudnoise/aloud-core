<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 17.01.2018
 * Time: 3:09
 */

namespace api\models\results;


use yii\db\Expression;

class MaterialResults extends \common\models\results\MaterialResults
{

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_UPDATE => ['process']
        ]);
    }

    public function rules()
    {
        return [
            [['process'], 'filter', 'filter' => function() {
                return new Expression('NOW()');
            }]
        ];
    }

    public function fields()
    {
        return ['process'];
    }

}