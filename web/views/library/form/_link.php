<label class="control-label page-header"><h3 class="text-info"><?=Yii::t("main","Вставьте ссылку")?></h3></label>
<div class="mtype-video" style="">
    <div class='form-group'>
        <label class="control-label"><?=Yii::t("main","Укажите ссылку на ресурс")?></label>
        <input class='form-control' name="external_link[name]" type='text' value="<?=isset($model->external_link['name']) ? $model->external_link['name'] : ""?>" placeholder='<?=Yii::t("main","ссылка")?>' />
    </div>
    <div class="form-group">
        <label class="control-label"><?=Yii::t("main","Укажите текст ссылки")?></label>
        <input class='form-control' name="external_link[text]" type='text' value="<?=isset($model->external_link['name']) ? $model->external_link['text'] : ""?>" placeholder='<?=Yii::t("main","текст ссылки")?>' />
    </div>
</div>