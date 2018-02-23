<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 17.01.2018
 * Time: 2:43
 */

namespace app\models\results;


class MaterialResults extends \common\models\results\MaterialResults
{

    public function fields()
    {
        return [
            'id',
            'action'
        ];
    }

}