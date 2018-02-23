<?php
$this->setTitle(Yii::t("main","Новый курс"), false);
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
?>

<div class="action-content">
    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions"=>[
            "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/courses/add"], \Yii::$app->request->get())),
            "method"=>"post",
            "id"=>"newCoursesForm"
        ],
    ]);
    ?>

    <div class="form-group mt-1" attribute="name">
        <label class="control-label"><?= $model->getAttributeLabel("name") ?></label>
        <input type="text" name="name" class="form-control" placeholder="<?=Yii::t("main","Введите название курса")?>" value="<?=$model->name?>" />
    </div>

    <div class="form-group mt-1"  attribute="description">
        <label class="control-label"><?= $model->getAttributeLabel("description") ?></label>
        <textarea name="description" class="form-control" placeholder="<?=Yii::t("main","Введите описание курса")?>"><?=$model->description?></textarea>
    </div>

    <div class="form-group" attribute="tagsString">
        <label class="control-label"><?= $model->getAttributeLabel("tagsString") ?></label>
        <input value="<?=$model->tagsString?>" class="form-control" name="tagsString" placeholder="<?= $model->getAttributeLabel("tagsString")?>" />
        <p class="help-block text-very-light-gray mt-1">
            <?=Yii::t("main","В данном поле необходимо написать как можно больше ключевых слов, относящихся к добавляемому курсу. Это облегчит поиск курсов")?>
        </p>
    </div>

    <div class="form-group" attribute="continuous">
        <?=\app\helpers\Html::activeCheckbox($model, 'continuous', [
            'name' => 'continuous',
        ])?>
        <p class="help-block text-very-light-gray mt-1">
            <?=Yii::t("main","Отметьте этот пункт, если должны быть завершены предыдущие уроки курса, чтобы приступить к следующему")?>
        </p>
    </div>

    <div class="submit-panel">
        <input type="submit" value="<?=Yii::t("main","Сохранить")?>" class="btn btn-primary btn-lg" />
    </div>


    <?php \app\widgets\EForm\EForm::end(); ?>
</div>
