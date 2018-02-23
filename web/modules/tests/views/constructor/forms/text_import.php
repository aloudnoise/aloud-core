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

<div class="panel-group mb-2" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="card">
        <div class="card-header" role="tab" id="headingOne">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <strong class="text-danger"><?=Yii::t("main","Перед тем как импортировать вопросы, ознакомьтесь с правилами оформления документа")?></strong>
            </a>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="card-body">
                <div class='clearfix'>
                    <iframe class="uploaded-document-iframe" src="https://docs.google.com/viewer?url=<?=Yii::$app->params['host']?><?=Yii::$app->assetManager->getBundle("base")->baseUrl."/other/test_import_template.doc"?>&embedded=true" width="100%" height="1200" style="border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="import-block">
    <div class="form-group" attribute="import">
        <textarea class="form-control" name="import" value="<?=$model->import?>" rows="10" placeholder="<?=Yii::t("main","Скопируйте весь текст из MC Word документа или любого другого текстового редактора (CTRL+A -> CTRL+C) и вставьте в данное текстовое поле (CTRL+V)")?>"></textarea>
    </div>

    <div class="form-group mb-0">
        <div class="row">
            <div class="col col-auto ml-auto">
                <a href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id])?>" class="text-muted btn btn-light"><?=Yii::t("main","Отмена")?></a>
                <button type="submit" class="pointer btn btn-success" name="parse"><?=Yii::t("main","Импортировать")?></button>
            </div>
        </div>
    </div>
</div>

<div class="parsed-block" style="display: none">

    <div class="parsed-alert mt-3">
        <div class="alert alert-success"><?=Yii::t("main","Распознано <count></count> вопросов.")?></div>

        <div class="parse-failed" style="display: none;">
            <div class="alert alert-warning">
                <h5 class="mb-2"><?=Yii::t("main","Не удалось распознать следующие вопросы")?></h5>
                <p class="failed-text">

                </p>
            </div>
        </div>

    </div>

    <div class="form-group mb-0" attribute="parsed">
        <input name="parsed" class="parsed-input" type="hidden" value='<?=$model->parsed?>' />
    </div>

    <div class="form-group mb-0 mt-3" attribute="theme_id">
        <label class="control-label"><?=Yii::t("main","Добавить вопросы в тему")?></label>
        <?=\app\helpers\Html::dropDownList("theme_id", $model->theme_id, \app\helpers\ArrayHelper::map((new \app\modules\tests\models\constructor\FullTheme())->getThemes(),'id','nameWithQCount'), [
            'class' => 'form-control chosen-select',
            'prompt' => '<Без темы>'
        ])?>
    </div>

    <div class="form-group mb-0 mt-3">
        <div class="row">
            <div class="col col-auto ml-auto">
                <a href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id])?>" class="text-muted btn btn-light"><?=Yii::t("main","Отмена")?></a>
                <button type="submit" class="pointer btn btn-success" name="save"><?=Yii::t("main","Сохранить")?></button>
            </div>
        </div>
    </div>
</div>

<?php \app\widgets\EForm\EForm::end(); ?>