<?php

(Yii::$app->assetManager->getBundle("tools"))::registerChosen($this);
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
(Yii::$app->assetManager->getBundle("bootstrap"))::registerTimePicker($this);
\Yii::$app->breadCrumbs->addLink(\Yii::t("main","Список материалов"), \app\helpers\OrganizationUrl::to(["/library/index"]));
$this->addTitle(Yii::t("main","Добавление материала"));

?>
<div class="action-content">

    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions"=>[
            "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/library/add"], \Yii::$app->request->get())),
            "method"=>"post",
            "id"=>"newMaterialForm"
        ],
    ]);
    ?>

    <input id="Materials_mtype" type="hidden" name="type" value="<?=$model->type?>" />

    <?php

    echo \app\widgets\EUploader\EUploader::widget([
    ]);
    echo \app\widgets\ECropper\ECropper::widget([
    ]);

    (Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();

    $types = array(
        \common\models\Materials::TYPE_FILE => [
            'label' => Yii::t("main","Файл"),
            'description' => Yii::t("main","Загрузите материал"),
            'icon' => 'file',
            'view' => '_files'
        ],
        \common\models\Materials::TYPE_VIDEO => [
            'label' => Yii::t("main","Видео"),
            'description' => Yii::t("main","Добавьте видео с сервисов Youtube или Vimeo"),
            'icon' => 'youtube',
            'view' => '_video'
        ],
        \common\models\Materials::TYPE_LINK => [
            'label' => Yii::t("main","Ссылка"),
            'description' => Yii::t("main","Интернет-ссылка на ЦОР"),
            'icon' => 'link',
            'view' => '_link'
        ],
        \common\models\Materials::TYPE_DER => [
            'label' => Yii::t("main","ЦОР"),
            'description' => Yii::t("main","ЦОР"),
            'icon' => 'der',
            'view' => '_der'
        ],
        \common\models\Materials::TYPE_CONFERENCE => [
            'label' => Yii::t("main","Вебинар"),
            'description' => Yii::t("main","Онлайн Вебинар"),
            'icon' => 'conference',
            'view' => '_conference'
        ],
    )
    ?>

    <div class="white-block">
        <div class="form-group material-body" attribute="info">
            <?php if (empty($model->type)) { ?>
                <div class="choose_mtype clearfix row justify-content-center">
                    <?php foreach ($types as $type => $type_data) { ?>
                    <div class="text-center col-4 mtype_panel pointer" mtype_id="<?=\common\models\Materials::TYPE_FILE?>" mtype="file">
                        <a class="d-block" href="<?=\app\helpers\OrganizationUrl::to(['/library/add', 'type' => $type])?>">
                            <div class="page-header mt-3 mb-3"><h3 class="text-light-gray"><?=$type_data['label']?></h3></div>
                            <img class="clearfix nonhover" style="max-height:200px;" src="<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/".$type_data['icon'].".png"?>" />
                            <img class="clearfix hover fadein" style="display:none; max-height:200px;" src="<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/".$type_data['icon']."_blue.png"?>" />
                            <div class="clearfix"></div>
                            <span style='display:block; margin-top:10px;' class="text-muted"><?=$type_data['description']?></span>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if ($model->type) { ?>
                <?=$this->render("form/".$types[$model->type]['view'], [
                    'model' => $model,

                ])?>
                <input name="type" type="hidden" value="<?=$model->type?>" />
            <?php } ?>

        </div>

        <?php if ($model->type) { ?>

            <div class="form-group mb-2" attribute="name">
                <label class="control-label"><?=$model->getAttributeLabel("name")?></label>
                <input class="form-control" name="name" value="<?=$model->name?>" placeholder="<?=$model->getAttributeLabel("name")?>" />
            </div>

            <div class="form-group mb-2" attribute="language">
                <label class="control-label"><?=$model->getAttributeLabel("language")?></label>
                <?=\app\helpers\Html::dropDownList('language', ($model->language ?: Yii::$app->language) , [
                    'ru-RU' => Yii::t("main","Русский"),
                    'kk-KZ' => Yii::t("main","Казахский"),
                ], [
                        'class' => 'form-control'
                ])?>
            </div>

            <div class="form-group mb-2" attribute="theme">
                <label class="control-label"><?=$model->getAttributeLabel("theme")?></label>
                <?=\app\helpers\Html::dropDownList('theme', $model->theme_id, \app\helpers\ArrayHelper::map(\app\models\DicValues::findByDic("DicQuestionThemes"), 'id', 'name'), [
                    'prompt' => Yii::t("main","Укажите тему обучения"),
                    'data-placeholder' => Yii::t("main","Укажите тему обучения"),
                    'class' => 'chosen-select form-control'
                ])?>
                <p class="mt-2 help-block text-muted"><?=Yii::t("main","Если в списке нету нужного варианта, впишите его в окно поиска в выпадающем списке и нажмите enter")?></p>
            </div>

            <div class="form-group mb-2" attribute="tagsString">
                <label class="control-label"><?=$model->getAttributeLabel("tagsString")?></label>
                <input value="<?=$model->tagsString?>" class="form-control" name="tagsString" placeholder="<?=Yii::t("main","Начните набирать слово...")?>" />
                <p class="help-block  text-very-light-gray"><?=Yii::t("main","В данном поле необходимо написать как можно больше ключевых слов, относящихся к добавляемому материалу. Это облегчит поиск материала")?></p>
            </div>

            <div class="form-group mb-2" attribute="description" style="margin-bottom:0px;">
                <label class="control-label"><?=Yii::t("main","Аннотация")?></label>
                <textarea rows="5" name="description" class="form-control"><?=$model->description?></textarea>
                <p class="help-block text-very-light-gray"><?=Yii::t("main","Аннотация должна быть краткой, но емкой, в ней должна содержаться информация о добавляемом материале")?></p>
            </div>



            <div style="margin-top:30px;" class="control-label page-header"><h3 class="text-info"><?=Yii::t("main","Завершение")?></h3></div>

            <!--
                    <div class="form-group">
                        <div class="alert alert-warning"><?=Yii::t("main","Перед опубликованием ваш материал должен пройти проверку модератором.")?></div>
                    </div> -->

            <div class="form-group" attribute="source">
                <label class="control-label"><?=$model->getAttributeLabel("source")?></label>
                <input value="<?=$model->source?>" class="form-control" name="source" placeholder="<?=$model->getAttributeLabel("source")?>" />
            </div>

            <div class="form-group" attribute="is_shared">
                <div class="custom-control custom-checkbox mb-0">
                    <input id="is_shared_input" <?=$model->is_shared ? "checked" : ($model->isNewRecord ? "checked" : "")?> type="checkbox" class="custom-control-input" name="is_shared">
                    <label for="is_shared_input" class="custom-control-label"><?=Yii::t("main","Общедоступный материал")?></label>
                </div>
                <p class="text-muted mt-2"><?=Yii::t("main","Слушатели смогут просматривать данный матерал, а учителя использовать в своих мероприятиях и курсах")?></p>
            </div>

            <div class="form-group" attribute="access_by_link">
                <div class="custom-control custom-checkbox mb-0">
                    <input id="access_by_link_input" <?=$model->access_by_link ? "checked" : ""?> type="checkbox" class="custom-control-input" name="access_by_link">
                    <label for="access_by_link_input" class="custom-control-label"><?=Yii::t("main","Доступ по прямой ссылке")?></label>
                </div>
                <p class="text-muted mt-2"><?=Yii::t("main","Любой незаргеистрированный пользователь сможет открыть данный материал, если у него будет ссылка на данный материал. Ссылку вы сможете скопировать, открыв материал в библиотеке")?></p>
            </div>

            <div class="mt-3 submit-panel" style="">
                <input type="submit" value="<?=Yii::t("main","Опубликовать")?>" class="btn btn-primary btn-lg" />
            </div>

        <?php } ?>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>

</div>