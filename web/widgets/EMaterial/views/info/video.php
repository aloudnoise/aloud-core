<?php
echo \app\widgets\Embed\EmbedVideo::widget(array(
    "video_id"=>$this->context->material->infoJson['video']['video_id'],
    "type"=>$this->context->material->infoJson['video']['type'],
    "width"=>$this->context->material->infoJson['video']['width'],
    "height"=>$this->context->material->infoJson['video']['height']
));
?>