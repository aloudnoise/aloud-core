<div class="page-header"><h5 class="text-center text-light-gray"><?=Yii::t("main","Выберите файл для загрузки…или перетащите его мышью")?></h5></div>
<div class="mtype-file" style="margin-top:15px;">

    <div class="uploader-block">

    </div>

</div>

<input type="hidden" name="file" type="text" class="file-input" value='<?=json_encode($model->file)?>' />