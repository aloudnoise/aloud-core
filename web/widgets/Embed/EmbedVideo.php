<?php

namespace aloud_core\web\widgets\Embed;

class EmbedVideo extends \aloud_core\web\components\Widget
{

    public $js = [
        '@app/widgets/Embed/assets/EmbedVideo.js'
    ];

    public $video_id = null;
    public $type = null;
    public $height = null;
    public $width = null;
    public function run()
    {
        return $this->render("embedVideo");
    }
}