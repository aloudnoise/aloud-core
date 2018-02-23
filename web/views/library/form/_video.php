<label class="control-label page-header"><h3 class="text-info"><?=Yii::t("main","Вставьте видео материал с Youtube или Vimeo")?></h3></label>
<div class="mtype-video" style="margin-top:15px;">

    <div class="video-block">



    </div>

</div>

<input type="hidden" name="video" class="video-value" value='<?=json_encode($model->video)?>' />

<div class="form-group mt-3" attribute="is_live">
    <div class="checkbox">
        <label><input type="checkbox" name="is_live" <?=$model->is_live ? "checked" : ""?> /> <?=Yii::t("main","Онлайн трансляция")?></label>
    </div>
</div>

<div class="row live-block"  style="<?=$model->is_live ? "" : "display: none;"?>">
    <div class="col-4">
        <div class="form-group" attribute="live_date">
            <label class="control-label"><?=Yii::t("main","Дата начала трансляции")?></label>
            <input class="form-control" type="text" name="live_date" value="<?=$model->live_date?>" />
        </div>
    </div>
    <div class="col-4">
        <div class="form-group" attribute="live_time">
            <label class="control-label"><?=Yii::t("main","Время начала трансляции")?></label>
            <input class="form-control" type="text" name="live_time" value="<?=$model->live_time?>" />
        </div>
    </div>
</div>

