<?php
$this->addTitle(Yii::t("main", "Написать в службу поддержки"));
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
?>
<div class="action-content">

    <div class="white-block">
        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions" => [
                "action" => \app\helpers\OrganizationUrl::to(array_merge(["/main/support"], \Yii::$app->request->get())),
                "method" => "post",
                "id" => "newSupportForm"
            ],
        ]);
        ?>
        <?php
        echo \app\widgets\EUploader\EUploader::widget([
            "standalone" => true
        ]);
        (Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();
        ?>
        <div class="form-group mb-2" attribute="contacts">
            <label class="control-label"><?=$model->getAttributeLabel("contacts")?></label>
            <input class="form-control" name="contacts" value="<?=$model->contacts?>" placeholder="<?=$model->getAttributeLabel("contacts")?>" />
            <p class="mt-2"><?=Yii::t("main","Укажите контакты, по которым с вами можно связаться (email, мобильный телефон)")?></p>
        </div>


        <div class="form-group mb-3" attribute="question">
            <label class="control-label"><?=$model->getAttributeLabel("question")?></label>
            <?=\app\helpers\Html::textarea("question", $model->question, [
                'id' => 'question',
                'textareatype'=>'ckeditor',
                'cktype' => 'full',
                "class"=>"form-control support-textarea",
            ])?>
            <p class="mt-2"><?=Yii::t("main","Опишите подробно проблему с которой вы столкнулись")?></p>
        </div>

        <div class="form-group mb-0">
            <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Отправить")?>" />
        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>

</div>