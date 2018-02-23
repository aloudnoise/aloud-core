<?php

namespace app\widgets\EProfile;

class EGroupProfile extends \app\components\Widget
{

    public $size = 'small';

    /**
     * If static, then model must be provided
     * @var null
     */
    public $model = null;

    public $htmlOptions = [];

    public $link = [];

    public $readonly = false;

    public function run()
    {

        if (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = "profile profile-".$this->size." clearfix";
        } else {
            $this->htmlOptions['class'] .= " profile profile-".$this->size." clearfix";
        }

        if ($this->model == null) return false;

        if ($this->type == self::TYPE_TEMPLATE)
        {
            $m = new \stdClass();
            $m->instance = $this->model;
            $m->id = "<%=".$m->instance.".id"."%>";
            $m->fio = "<%=".$m->instance.".name"."%>";
            $this->model = $m;
        } else if ($this->type !== self::TYPE_STATIC) {
            return $this->render("group_".$this->type);
        }

        return $this->render("group_profile");

    }

}

?>