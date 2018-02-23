<?php
\Yii::$app->breadCrumbs->addLink(\Yii::t("main","Управление мероприятиями"), \app\helpers\OrganizationUrl::to(["/events/index"]));
$this->addTitle( (\Yii::$app->request->get('id') )?(Yii::t("main","Редактирование мероприятия")):(Yii::t("main","Добавление мероприятия")));
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
(Yii::$app->assetManager->getBundle("tools"))::registerChosen($this);

?>

<div class="action-content">

    <div class="row big-gutter">

        <?php if (!$this->context->isModal) { ?>
            <div class="col-4">

                <?php $this->context->external = true;?>
                <?=$this->context->runAction('calendar')?>

                <?php $this->context->external = true;?>
                <?=$this->context->runAction('list')?>

                <?php $this->context->external = false; ?>

            </div>
        <?php } ?>

        <div class="col">

            <h3 class="modal-hidden mb-3"><?=$model->isNewRecord ? Yii::t("main","Новое мероприятие") : Yii::t("main","Редактирование мероприятия")?></h3>

            <div class="white-block">
                <?php
                $f = \app\widgets\EForm\EForm::begin([
                    "htmlOptions"=>[
                        "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/events/add"], \Yii::$app->request->get(null, []))),
                        "method"=>"post",
                        "id"=>"newEventForm"
                    ],
                ]);
                ?>

                <div class="form-group mb-3" attribute="name">

                    <label for="name" class="control-label"><?=$model->getAttributeLabel("name")?></label>
                    <input class="form-control" type="text" placeholder="<?=$model->getAttributeLabel("name")?>" id="name" value="<?=$model->name?>" name="name" />

                </div>

                <div class="form-group mb-3" attribute="begin_ts">
                    <label for="begin_ts_display" class="control-label"><?=$model->getAttributeLabel("begin_ts_display")?></label>
                    <input fixed="true" class="form-control date" style="width:150px" autocomplete="off" name="begin_ts" id="begin_ts" value="<?=$model->begin_ts_display?>" type="text">
                </div>
                <div class="form-group mb-3" attribute="end_ts">
                    <label for="end_ts_display" class="control-label"><?=$model->getAttributeLabel("end_ts_display")?></label>
                    <input fixed="true" class="form-control date" style="width:150px" autocomplete="off" name="end_ts" id="end_ts" value="<?=$model->end_ts_display?>" type="text">
                </div>

                <?php $education_views = \app\models\DicValues::findByDic("education_views");
                if ($education_views) { ?>

                    <div class="form-group" attribute="education_view">
                        <label class="control-label"><?=Yii::t("main","Вид обучения")?></label>
                        <?=\app\helpers\Html::dropDownList("education_view", $model->education_view, \app\helpers\ArrayHelper::map($education_views, 'id', 'nameByLang'), [
                                'class' => 'form-control'
                        ])?>
                    </div>

                <?php } ?>

                <div class="form-group" attribute="subject">
                    <label class="control-label"><?=Yii::t("main","Предмет")?></label>
                    <?=\app\helpers\Html::dropDownList("subject", $model->subject_id, \app\helpers\ArrayHelper::map(\app\models\DicValues::findByDic("subjects"), 'id', 'nameByLang'), [
                        'prompt' => Yii::t("main","Укажите предмет"),
                        'data-placeholder' => Yii::t("main","Укажите предмет"),
                        'class' => 'form-control chosen-select'
                    ])?>
                    <p class="mt-2 help-block text-muted"><?=Yii::t("main","Если в списке нету нужного варианта, впишите его в окно поиска в выпадающем списке и нажмите enter")?></p>
                </div>

                <div class="form-group mb-3"  attribute="description">
                    <textarea name="description" class="form-control" placeholder="<?=Yii::t("main","Введите описание мероприятия")?>"><?=$model->description?></textarea>
                </div>

                <div class="form-group mb-3" attribute="tagsString">
                    <label class="control-label"><?=Yii::t("main","Ключевые слова")?></label>
                    <input value="<?=$model->tagsString?>" class="form-control" name="tagsString" placeholder="<?=Yii::t("main","Начните набирать слово...")?>" />
                    <p class="help-block text-very-light-gray mt-1"><?=Yii::t("main","В данном поле необходимо написать как можно больше ключевых слов, относящихся к добавляемому тесту. Это облегчит поиск тестов")?></p>
                </div>

                <div class="mt-3 submit-panel">

                    <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />
                    <?php if ($model->isNewRecord) { ?>
                        <a class="btn btn-outline-danger" href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/events/index'], Yii::$app->request->get()))?>"><?=Yii::t("main","Отмена")?></a>
                    <?php } ?>

                </div>

                <?php \app\widgets\EForm\EForm::end(); ?>
            </div>

        </div>


    </div>

</div>









