<?php
$this->setTitle($model->isNewRecord ? Yii::t("main","Новый тест") : $model->name);
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
?>

<div class="action-content">
    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions"=>[
            "action"=>app\helpers\OrganizationUrl::to(array_merge(["/tests/base/add"], \Yii::$app->request->get())),
            "method"=>"post",
            "id"=>"newTestsForm"
        ],
    ]);
    ?>

    <div class="white-block">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="form-group" attribute="name">
                    <label class="control-label"><?=Yii::t("main","Название теста")?></label>
                    <input value="<?=$model->name?>" type="text" name="name" class="form-control" placeholder="<?=Yii::t("main","Введите название теста")?>" />
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group" attribute="qcount">
                    <label class="control-label"><?=Yii::t("main","Количество вопросов")?></label>
                    <input value="<?=$model->qcount?>" type="text" name="qcount" class="form-control" placeholder="<?=Yii::t("main","#")?>" />
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group" attribute="time">
                    <label class="control-label"><?=Yii::t("main","Время на прохождение")?></label>
                    <input value="<?=$model->time?>" type="text" name="time" class="form-control" placeholder="<?=Yii::t("main","# Минут")?>" />
                </div>
            </div>
        </div>

        <div class="form-group mb-2" attribute="description">
            <textarea name="description" class="form-control" placeholder="<?=Yii::t("main","Введите описание теста")?>"><?=$model->description?></textarea>
        </div>

        <div class="form-group mb-3" attribute="tagsString">
            <label class="control-label"><?=Yii::t("main","Ключевые слова")?></label>
            <input value="<?=$model->tagsString?>" class="form-control" name="tagsString" placeholder="<?=Yii::t("main","Начните набирать слово...")?>" />
            <p class="help-block text-very-light-gray mt-0"><?=Yii::t("main","В данном поле необходимо написать как можно больше ключевых слов, относящихся к добавляемому тесту. Это облегчит поиск тестов")?></p>
        </div>

        <div class="form-group mb-3" attribute="is_repeatable">
            <div class="checkbox">
                <label class="control-label"><input type="checkbox" name="is_repeatable" <?=$model->is_repeatable ? "checked" : ""?> /> <?=Yii::t("main","Повторяющийся")?></label>
            </div>
            <p class="help-block text-very-light-gray mt-0"><?=Yii::t("main","Каждый тестируемый сможет проходить тест сколько угодно раз.")?></p>
        </div>

        <div class="form-group mb-3" attribute="random">
            <div class="checkbox">
                <label class="control-label"><input type="checkbox" name="random" <?=$model->random ? "checked" : ""?> /> <?=Yii::t("main","Вопросы и варианты ответов в случайном порядке")?></label>
            </div>
            <p class="help-block text-very-light-gray mt-0"><?=Yii::t("main","Для каждого тестируемого вопросы будут перемешаны в случайном порядке.")?></p>
        </div>

<!--        <div class="form-group mb-3" attribute="protected">-->
<!--            <div class="checkbox">-->
<!--                <label class="control-label"><input type="checkbox" name="protected" --><?//=$model->protected ? "checked" : ""?><!-- /> --><?//=Yii::t("main","Защищенный тест")?><!--</label>-->
<!--            </div>-->
<!--            <p class="help-block text-very-light-gray mt-0">--><?//=Yii::t("main","Тест будет доступен только из Десктопного приложения. Данная опция предназначена для защиты от \"жульничества\"")?><!--</p>-->
<!--        </div>-->

        <div class="form-group text-center" style="margin-top:45px;">
            <input type="submit" value="<?=Yii::t("main","Сохранить")?>" class="btn btn-primary btn-lg" /><a style="margin-left:15px;" href="<?=!$model->isNewRecord ? app\helpers\OrganizationUrl::to(["/tests/base/view", 'id' => $model->id]) : app\helpers\OrganizationUrl::to(["/tests/base/index"])?>" class="btn btn-warning btn-lg" ><?=Yii::t("main","Назад")?></a>
        </div>
    </div>
    <?php \app\widgets\EForm\EForm::end(); ?>
</div>