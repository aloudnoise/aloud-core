<div class='embedded-video'>
    <?php if ($this->context->type == "youtube") { ?>

        <?php
        $this->registerJsFile("https://www.youtube.com/player_api", [
            'position' => \yii\web\View::POS_END
        ], "youtube_js");
        ?>

        <div class="embed-responsive embed-responsive-16by9">
            <div id="<?=$this->context->video_id?>" class="youtube-video">
            </div>
        </div>
    <?php } else if ($this->context->type == "vimeo") { ?>
        <div class="vimeo-video">
            <iframe src="http://player.vimeo.com/video/<?=$this->context->video_id?>" width="<?=$this->context->width?>" height="<?=$this->context->height?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    <? } ?>
</div>