<?php
\Yii::$app->breadCrumbs->addLink(\Yii::t("main", "Новости"), \app\helpers\OrganizationUrl::to(["/news/index"]));
if (\Yii::$app->request->get('id')) {
    $this->addTitle((Yii::t("main", "Редактирование новости")));
} else {
    $this->addTitle((Yii::t("main", "Добавление новости")));
}
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
?>
<div class="action-content">
    <div class="row">
        <div class="col">

            <div class="mb-3"><h3><?= Yii::t("main", "Новость") ?></h3></div>

            <div class="white-block">
                <?php
                $f = \app\widgets\EForm\EForm::begin([
                    "htmlOptions" => [
                        "action" => \app\helpers\OrganizationUrl::to(array_merge(["/news/add"], \Yii::$app->request->get())),
                        "method" => "post",
                        "id" => "newForm"
                    ],
                ]);
                echo \app\widgets\EUploader\EUploader::widget([
                    "standalone" => true
                ]);
                echo \app\widgets\ECropper\ECropper::widget([
                ]);
                (Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();
                ?>

                <script id="attached_file_template" type="text/template">
                    <div class='uploaded-file'>
                        <% if (!data.error && data.percent>0 && data.percent < 100) { %>
                        <div style='margin-top:5px; margin-bottom:5px;' class="progress progress-striped">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                 aria-valuemax="100" style="width: <%=data.percent%>%">
                                <span><%=(Math.ceil(data.loaded/1024)) + "kb" %>/<%=(Math.ceil(data.total/1024)) + "kb"%></span>
                            </div>
                        </div>
                        <% } %>
                    </div>
                </script>

                <div class="uploader mb-3" style="width:150px;">
                    <input type="hidden" name="image" id="image" value="<?=$model->image?>"/>
                    <div class="uploaded-photo">
                        <?php if ($model->image!= "") { ?>
                            <img class="img-thumbnail" style="max-width:150px;"
                                 alt="<?=Yii::t('main', 'Фото новости')?>" src="<?=$model->image?>">
                        <?php } else { ?>
                            <div style="width:150px; height:150px; border:1px solid;" class="bg-light justify-content-center border-secondary">
                                <div class="row" style="height:150px;">
                                    <div class="align-self-center col text-center text-uppercase">
                                        <?=Yii::t("main","Фото")?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="text-center">
                        <a style="margin-top:-45px;" class="btn btn-info upload-button pointer text-white"><li class="fa fa-image"></li></a>
                    </div>
                    <input style="display:none" type="file" name="file"/>
                </div>

                <div class="form-group mb-3" attribute="name">
                    <label for="name" class="control-label"><?=$model->getAttributeLabel("name")?></label>
                    <input class="form-control" type="text" placeholder="<?=$model->getAttributeLabel("name")?>" id="name" value="<?=$model->name?>" name="name" />
                </div>

                <div class="form-group mb-3" attribute="content">
                    <label class="control-label"><?=$model->getAttributeLabel("content")?></label>
                    <?=\app\helpers\Html::textarea("content", $model->content, [
                        'id' => 'content',
                        'textareatype'=>'ckeditor',
                        'cktype' => 'full',
                        "class"=>"form-control new-textarea",
                        "placeholder"=>Yii::t("main","Введите сюда текст новости")
                    ])?>
                </div>

                <div class="form-group mb-3" attribute="tagsString">
                    <label class="control-label"><?=Yii::t("main","Ключевые слова")?></label>
                    <input value="<?=$model->tagsString?>" class="form-control" name="tagsString" placeholder="<?=Yii::t("main","Начните набирать слово...")?>" />
                    <p class="help-block text-very-light-gray mt-1"><?=Yii::t("main","В данном поле необходимо написать как можно больше ключевых слов, относящихся к добавляемой новости. Это облегчит поиск новостей")?></p>
                </div>

                <div class="mt-5 submit-panel">

                    <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />
                    <?php if ($model->isNewRecord) { ?>
                        <a class="btn btn-outline-danger" href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/news/index'], Yii::$app->request->get()))?>"><?=Yii::t("main","Отмена")?></a>
                    <?php } ?>

                </div>
                <?php \app\widgets\EForm\EForm::end(); ?>
            </div>
        </div>
    </div>
</div>









