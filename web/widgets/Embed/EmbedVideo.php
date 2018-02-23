<?php

namespace app\widgets\Embed;

class EmbedVideo extends \app\components\Widget
{
    public $backbone = true;
    public $video_id = null;
    public $type = null;
    public $height = null;
    public $width = null;
    public function run()
    {
        return $this->render("embedVideo");
    }
}