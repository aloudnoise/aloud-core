<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 04.01.2018
 * Time: 0:06
 */

namespace app\widgets\EProfile;


use app\components\Widget;

class ESideProfile extends Widget
{

    public $model = null;

    public function run()
    {



        return $this->render("side", [
            'model' => $this->model
        ]);

    }

}