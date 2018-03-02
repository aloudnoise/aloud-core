<?php

namespace aloud_core\web\widgets\Embed;

class EmbedVideo extends \aloud_core\web\components\Widget
{

    public $assets_path = '@aloud_core/web/widgets/Embed/assets';
    public $js = [
        'EmbedVideo.js'
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