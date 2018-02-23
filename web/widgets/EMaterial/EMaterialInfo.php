<?php

namespace app\widgets\EMaterial;

use app\models\Materials;

/**
 * Виджет авторизации пользователя
 * Проверяет, если пользователь не авторизован, предлагает ему авторизоваться
 * В противном случае показываем мини профайл с ссылкой
 *
 * Class EAuth
 */
class EMaterialInfo extends \app\components\Widget
{

    public $material = null;

    public $link = array();

    public function run()
    {

        $types = [
            Materials::TYPE_LINK => "link",
            Materials::TYPE_VIDEO => "video",
            Materials::TYPE_FILE => "file",
            Materials::TYPE_DER => "der",
            Materials::TYPE_CONFERENCE => "conference"
        ];

        return $this->render("info/".$types[$this->material->type]);

    }

}
?>