<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 06.01.2018
 * Time: 22:01
 */

namespace aloud_core\web\widgets\EMessages;


use aloud_core\web\components\Widget;

class EMessages extends Widget
{

    public $backbone = true;

    public function run()
    {
        return $this->render("index");
    }

}