<?=$this->setTitle("Сообщения")?>
<div class="action-content">

    <div class="row big-gutter">
        <div class="col-7">
            <div class="white-block">
                <div class="row">
                    <div class="col align-self-center">
                        <a href="<?=\app\helpers\OrganizationUrl::to(['/messages/index'])?>" class="btn btn-outline-danger btn-sm"><i class="fa fa-arrow-left"></i> <?=Yii::t("main","Назад")?></a>
                    </div>
                    <div class="col-auto align-self-center ml-auto">
                        <?=$this->render("@app/views/common/chat", [
                            'chat' => $chat
                        ])?>
                    </div>
                </div>
            </div>

            <div class="white-block p-0 mt-1">
                <div class="chat-container slim-scroll p-4" start="bottom" container_id="<?=$chat->id?>">
                    <?php if ($messages) { ?>

                        <?php foreach ($messages as $i => $message) { ?>

                            <?=$this->render("@app/views/common/message", [
                                    'message' => $message,
                                    'user' => $chat->members[$message->user_id]->user,
                                    'last' => count($messages) == $i+1 ? true : false
                            ])?>

                        <?php } ?>

                    <?php } else { ?>
                        <div class="alert alert-warning mt-1">
                            <?=Yii::t("main","Сообщений не найдено")?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="white-block mt-1">
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

                <div class="row">
                    <div class="col">
                        <div class="form-group mb-0" attribute="content">
                            <?=\app\helpers\Html::textarea("message", $model->message, [
                                'id' => 'message',
                                "class"=>"form-control",
                                "placeholder"=>Yii::t("main","Введите сюда текст сообщения"),
                                "rows" => 1,
                            ])?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-group mb-0" style="">
                            <button type="submit" class="btn btn-xs btn-success" ><?=Yii::t("main","Отправить")?></button>
                        </div>
                    </div>
                </div>


                <!--    <div class="uploader mt-3 mb-3">-->
                <!--        <h6 class="">--><?//=Yii::t("main","Прикрепить файл")?><!-- <a class="pointer text-white ml-3 btn btn-success btn-sm upload-button">--><?//=Yii::t("main","Прикрепить")?><!--</a></h6>-->
                <!--        <input style="display:none" type="file" name="file" />-->
                <!--        <div class="uploaded-loader"></div>-->
                <!--        <div class="uploaded-list">-->
                <!--        </div>-->
                <!--    </div>-->



                <?php \app\widgets\EForm\EForm::end(); ?>
            </div>
        </div>
        <div class="col-5">
            <? $this->context->external = true; ?>
            <?=$this->context->runAction('list'); ?>
        </div>
    </div>

</div>