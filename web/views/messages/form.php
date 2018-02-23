<?php
$this->addTitle(Yii::t("main", "Новое сообщение"));
?>
<div class="action-content">

    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions" => [
            "action" => \app\helpers\OrganizationUrl::to(array_merge(["/messages/add"], \Yii::$app->request->get())),
            "method" => "post",
            "id" => "newMessageForm"
        ],
    ]);
    ?>
    <?php
    echo \app\widgets\EUploader\EUploader::widget([
        "standalone" => true
    ]);
    (Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();
    ?>

    <div class="form-group p-3 bg-light" style="margin:-15px -15px 0 -15px;" attribute="user_id">
        <div class="row">
            <div class="align-self-center col col-auto">
                <h5 class=""><?=Yii::t("main","Адресат:")?></h5>
            </div>
            <div class="col">
                <?=\app\widgets\EProfile\EProfile::widget([
                    'model' => $user,
                    "link" => false
                ])?>
            </div>
        </div>
    </div>

    <div class="form-group mb-2 mt-4" attribute="content">
        <?=\app\helpers\Html::textarea("message", $model->message, [
            'id' => 'message',
            "class"=>"form-control",
            "placeholder"=>Yii::t("main","Введите сюда текст сообщения"),
            "rows" => 6,
        ])?>
    </div>

<!--    <div class="uploader mt-3 mb-3">-->
<!--        <h6 class="">--><?//=Yii::t("main","Прикрепить файл")?><!-- <a class="pointer text-white ml-3 btn btn-success btn-sm upload-button">--><?//=Yii::t("main","Прикрепить")?><!--</a></h6>-->
<!--        <input style="display:none" type="file" name="file" />-->
<!--        <div class="uploaded-loader"></div>-->
<!--        <div class="uploaded-list">-->
<!--        </div>-->
<!--    </div>-->

    <div class="mt-3 submit-panel" style="">
        <input type="submit" value="<?=Yii::t("main","Отправить")?>" class="btn btn-primary btn-lg" />
    </div>

    <?php \app\widgets\EForm\EForm::end(); ?>

</div>