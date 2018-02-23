<?php
$this->setTitle(Yii::t("main","Сбросить результат"));
?>

<div class="action-content">
    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions"=>[
            "action"=>app\helpers\OrganizationUrl::to(array_merge(["/tasks/discard"], \Yii::$app->request->get())),
            "method"=>"post",
            "id"=>"discardForm"
        ],
    ]);
    ?>

    <div class="form-group" attribute="status_note">
        <label class="control-label"><?=$model->getAttributeLabel("status_note")?></label>
        <textarea class="form-control" name="status_note" placeholder="<?=Yii::t("main","Опишите причину сброса результата")?>"></textarea>
    </div>

    <div class="form-group mt-2">
        <input type="submit" value="<?=Yii::t("main","Сбросить результат")?>" class="btn btn-primary btn-lg" />
    </div>
    <?php \app\widgets\EForm\EForm::end(); ?>
</div>