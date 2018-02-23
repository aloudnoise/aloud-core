<?php
namespace app\components;

use yii\base\Component;

class CacheDependencies extends Component
{

    public function sample()
    {

        $dependancies = [
            'v1/marks/current' => [
                [
                    'uin' => 'asd'
                ]
            ]
        ];

    }


}