<div class="page-header"><h5 class="text-center text-light-gray"><?=Yii::t("main","Выберите файл для загрузки…или перетащите его мышью")?></h5></div>
<div class="mtype-file" style="margin-top:15px;">

    <div class="uploader-block">

    </div>

</div>
<div class="der-help-block">
    <p class="  help-block text-very-light-gray">
        <?=Yii::t("main","ЦОР должен быть оформлен по правилам TinCan Course Activity. Пакет может содержать основную информацию в файле tincan.xml. Пакет должен быть заархиварован в zip архив.")?>
    </p>

    <input type="hidden" name="activity_id" value="<?=$model->activity_id?>" />

</div>

<input type="hidden" name="file" type="text" class="file-input" value='<?=json_encode($model->file)?>' />
