<?php

namespace aloud_core\web\widgets\ECaptcha;


use yii\captcha\Captcha;

class ECaptcha extends Captcha {

    public $captchaAction = 'auth/captcha';
    public $imageOptions = ['placeholder' => 'Код безопасности'];
    public $template =  '<div class="col-5">{image}</div><div class="col-7 mt-3">{input}</div>';
}