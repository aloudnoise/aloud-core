<?php

namespace app\models;

use app\traits\BackboneRequestTrait;

class RestoreToken extends \common\models\RestoreToken
{

    use BackboneRequestTrait;

    /**
     * При окончании работы с токеном, удаляем его. При необходимости можно оставить и проставить в used_ts время использования
     *
     * @throws \Exception
     */
    public function close() {
        return $this->delete();
    }
}