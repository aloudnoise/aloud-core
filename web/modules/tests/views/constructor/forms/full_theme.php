<?php
$f = \app\widgets\EForm\EForm::begin([
    "htmlOptions"=>[
        "action"=>app\helpers\OrganizationUrl::to(array_merge(["/tests/constructor/compile"], \Yii::$app->request->get())),
        "method"=>"post",
        "id"=>"instrumentForm"
    ],
]);

(Yii::$app->assetManager->getBundle("tools"))::registerChosen($this);
?>

<div class="form-group" attribute="theme_id">
    <label class="control-label"><?=Yii::t("main","Выберите тему")?></label>
    <?=\app\helpers\Html::dropDownList("theme_id", $model->theme_id, \app\helpers\ArrayHelper::map($model->getThemes(),'id','nameWithQCount'), [
        'class' => 'form-control chosen-select'
    ])?>
</div>

<div class="row">
    <div class="col col-6">
        <div class="form-group" attribute="weight">
            <label class="control-label"><?=Yii::t("main","Ставка")?></label>
            <input type="text" name="weight" value="<?=$model->weight?>" class="form-control" />
            <p class="help-block text-muted mt-2"><?=Yii::t("main","Не более какого процента или количества вопросов из данной темы, которые войдут в тестирование")?></p>
        </div>
    </div>
    <div class="col col-6">
        <div class="form-group" attribute="weight_type">
            <label class="control-label"><?=Yii::t("main","Тип ставки")?></label>
            <?=\app\helpers\Html::dropDownList("weight_type", $model->weight_type, $model->getWeightTypes(), [
                'class' => 'form-control'
            ])?>
        </div>
    </div>
</div>

<div class="form-group mb-0 mt-3">
    <div class="row">
        <div class="col col-auto ml-auto">
            <a href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id])?>" class="text-muted btn btn-light"><?=Yii::t("main","Отмена")?></a>
            <button type="submit" class="pointer btn btn-success" name="parse"><?=Yii::t("main","Прикрепить")?></button>
        </div>
    </div>
</div>

<?php \app\widgets\EForm\EForm::end(); ?>