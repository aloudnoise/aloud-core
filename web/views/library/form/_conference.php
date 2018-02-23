<div class="row live-block">
    <div class="col-6">
        <div class="form-group mb-0" attribute="live_date">
            <label class="control-label"><?=Yii::t("main","Дата начала вебинара")?></label>
            <input class="form-control" type="text" name="live_date" value="<?=$model->live_date?>" />
        </div>
    </div>
    <div class="col-6">
        <div class="form-group mb-0" attribute="live_time">
            <label class="control-label"><?=Yii::t("main","Время начала вебинара")?></label>
            <input class="form-control" type="text" name="live_time" value="<?=$model->live_time?>" />
        </div>
    </div>
</div>

<div class="form-group mb-0 mt-3" attribute="welcome">
    <label class="control-label"><?=Yii::t("main","Приветственное сообщение")?></label>
    <textarea class="form-control" name="welcome" rows="4"><?=$model->welcome?></textarea>
    <p class="mt-2"><small class="text-muted"><?=Yii::t("main","Не более 300 символов")?></small></p>
</div>

<div class="page-header mt-3"><h6 class="text-center text-light-gray"><?=Yii::t("main","Вы также можете загрузить презентацию в PDF, которая будет показана при открытии вебинара")?></h6></div>
<div class="mtype-conference" style="margin-top:15px;">

    <div class="uploader-block">

    </div>

</div>

<input type="hidden" name="presentation" type="text" class="file-input" value='<?=json_encode($model->presentation)?>' />