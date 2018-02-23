<?php
Yii::$app->breadCrumbs->addLink(Yii::t("main", "Список заданий"), \app\helpers\OrganizationUrl::to(["/tasks/index"]));
$this->addTitle(Yii::t("main", "Добавление задания"));
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
?>
<div class="action-content">

    <div class="white-block">
        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions" => [
                "action" => \app\helpers\OrganizationUrl::to(array_merge(["/tasks/add"], \Yii::$app->request->get())),
                "method" => "post",
                "id" => "newTasksForm"
            ],
        ]);
        ?>
        <?php
        echo \app\widgets\EUploader\EUploader::widget([
            "standalone" => true
        ]);
        (Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();
        ?>
        <div class="form-group mb-2" attribute="name">
            <label class="control-label"><?=$model->getAttributeLabel("name")?></label>
            <input class="form-control" name="name" value="<?=$model->name?>" placeholder="<?=$model->getAttributeLabel("name")?>" />
        </div>

        <div class="form-group mb-3" attribute="content">
            <label class="control-label"><?=$model->getAttributeLabel("content")?></label>
            <?=\app\helpers\Html::textarea("content", $model->content, [
                'id' => 'content',
                'textareatype'=>'ckeditor',
                'cktype' => 'full',
                "class"=>"form-control task-textarea",
                "placeholder"=>Yii::t("main","Введите сюда текст задания")
            ])?>
        </div>

        <script type="text/template" id="file_template">
            <div class="alert alert-secondary attached-file mb-2">
                <div class="row">
                    <div class="col-auto">
                        <input type="hidden" value='<%=JSON.stringify(data.model.toJSON())%>' name="files[]" />
                        <a target='_blank' href="<%=data.model.get("url")%>"><%=data.model.get("name")%></a>
                    </div>
                    <div class="col-auto ml-auto">
                        <a style= cursor:pointer;" class="text-danger delete-file btn-link"><strong><i class="fa fa-times"></i></strong></a>
                    </div>
                </div>
            </div>
        </script>

        <script id="attached_file_template" type="text/template">
            <div class='uploaded-file'>
                <% if (!data.error && data.percent>0 && data.percent < 100) { %>
                <div style='margin-top:5px; margin-bottom:5px;' class="progress progress-striped">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <%=data.percent%>%">
                        <span><%=(Math.ceil(data.loaded/1024)) + "kb" %>/<%=(Math.ceil(data.total/1024)) + "kb"%></span>
                    </div>
                </div>
                <% } %>
            </div>
        </script>

        <div class="uploader mb-4 mt-3">
            <h5 class="mb-2"><?=Yii::t("main","Прикрепленные документы")?> <a class="ml-2 text-white pointer btn btn-sm btn-primary upload-button"><?=Yii::t("main","Прикрепить")?></a></h5>

            <input style="display:none" type="file" name="file" />
            <div class="uploaded-loader"></div>
            <div class="uploaded-list">

            </div>
        </div>

        <div class="form-group" attribute="time">
            <label class="control-label"><?=Yii::t("main","Время на прохождение")?></label>
            <input value="<?=$model->time?>" type="text" name="time" class="form-control" placeholder="<?=Yii::t("main","# Минут")?>" />
        </div>

        <div class="form-group mb-3" attribute="tagsString">
            <label class="control-label"><?=Yii::t("main","Ключевые слова")?></label>
            <input value="<?=$model->tagsString?>" class="form-control" name="tagsString" placeholder="<?=Yii::t("main","Начните набирать слово...")?>" />
            <p class="help-block text-very-light-gray mt-1"><?=Yii::t("main","В данном поле необходимо написать как можно больше ключевых слов, относящихся к добавляемому тесту. Это облегчит поиск тестов")?></p>
        </div>

        <div class="form-group" attribute="is_shared">
            <div class="custom-control custom-checkbox mb-0">
                <input id="is_shared_input" <?=$model->is_shared ? "checked" : ($model->isNewRecord ? "checked" : "")?> type="checkbox" class="custom-control-input" name="is_shared">
                <label for="is_shared_input" style="margin-top:3px;" class="custom-control-label"><?=Yii::t("main","Общедоступное задание")?></label>
            </div>
        </div>

        <div class="form-group mb-0">
            <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />
            <a target="normal" class="btn btn-outline-danger" href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/tasks/index'], Yii::$app->request->get()))?>"><?=Yii::t("main","Отмена")?></a>
        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>

</div>