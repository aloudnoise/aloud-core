<?php
namespace api\models;

use common\models\forms\PhpWordHtmlRender;

class Export extends PhpWordHtmlRender
{

    public $type = 'word';

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['type'], 'safe']
        ]);
    }

    public function save()
    {

        if (!$this->validate()) {
            return false;
        }

        if ($this->type == "word") {
            return $this->render();
        }

    }

}