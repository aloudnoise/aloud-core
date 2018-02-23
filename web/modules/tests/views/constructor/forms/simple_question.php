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

<div class="form-group" attribute="name">
    <div class="row">
        <div class="col col-auto align-self-center">
            <h5 class="control-label mb-1"><?=Yii::t("main","Вопрос")?></h5>
        </div>
        <div class="col">
            <textarea textareatype="ckeditor" cktype="inline" class="form-control" id="question_name" name="name" rows="1" placeholder="<?=Yii::t("main","Введите свой вопрос")?>"><?=$model->name?></textarea>
        </div>
    </div>
</div>

<hr style="margin-left:-15px; margin-right:-15px;" />

<div class="answers form-group" attribute="answers">

    <h5 class="mb-4"><?=Yii::t("main","Варианты ответа")?></h5>

    <?php
        $answers = $model->answers ?: [
            ['id' => '', 'is_correct' => 0, 'name' => ''],
            ['id' => '', 'is_correct' => 0, 'name' => ''],
            ['id' => '', 'is_correct' => 0, 'name' => '']
        ]
    ?>

    <?php $i = 1; foreach ($answers as $answer) { ?>
    <div class="answer mb-3" i="<?=$i?>">
        <input type="hidden" name="answers[<?=$i?>][id]" value="<?=$answer['id']?>" />
        <div class="row">
            <div class="col col-auto align-self-center">
                <label class="custom-control custom-checkbox mr-0" title="<?=Yii::t("main","Правильный/Неправильный")?>">
                    <input <?=$answer['is_correct'] ? "checked" : ""?> type="checkbox" class="custom-control-input answer-correct" name="answers[<?=$i?>][is_correct]">
                    <span class="custom-control-indicator" style="width:1.5rem; height:1.5rem;"></span>
                </label>
            </div>
            <div class="col align-self-center">
                <textarea textareatype="ckeditor" cktype="inline"  id="answer_name_<?=$i?>" class="form-control answer-name mb-0" name="answers[<?=$i?>][name]" rows="1" placeholder="<?=Yii::t("main","Введите вариант ответа")?>"><?=$answer['name']?></textarea>
            </div>
            <div class="col-auto text-nowrap align-self-center">
                <a class="pointer add-variant text-muted " title="<?=Yii::t("main","Добавить вариант ответа")?>" style="font-size:1.2rem;"><strong><i class="fa fa-plus"></i></strong></a>
                <a class="ml-2 pointer delete-variant text-muted " title="<?=Yii::t("main","Удалить вариант ответа")?>" style="font-size:1.2rem;"><strong><i class="fa fa-minus"></i></strong></a>
            </div>
        </div>
    </div>
    <?php $i++; } ?>

</div>

<hr style="margin-left:-15px; margin-right:-15px;" />

<div class="options">

    <h5 class="mb-4"><?=Yii::t("main","Параметры")?></h5>

    <div class="row">
        <div class="col-2  align-self-center">
            <div class="row" title="<?=Yii::t("main","Количество баллов за правильный ответ")?>">
                <div class="col col-auto align-self-center">
                    <label class="mb-1 control-label"><?=Yii::t("main","Вес")?></label>
                </div>
                <div class="col">
                    <div class="form-group mb-0" attribute="weight">
                        <input type="text" class="form-control form-control-sm" placeholder="<?=Yii::t("main","1")?>" value="<?=$model->weight?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col align-self-center ml-3">
            <div class="form-group mb-0" attribute="is_random">
                <label class="custom-control custom-checkbox mb-0">
                    <input <?=$model->is_random ? "checked" : ""?> type="checkbox" class="custom-control-input answer-correct" name="is_random">
                    <span class="custom-control-indicator"></span>
                    <span style="margin-top:3px;" class="custom-control-description"><?=Yii::t("main","Варианты в случайном порядке")?></span>
                </label>
            </div>
        </div>
        <div class="col ml-auto align-self-center">
            <div class="form-group mb-0" attribute="theme_id">
                <div class="row">
                    <div class="col">
                        <?=\app\helpers\Html::dropDownList("theme_id", $model->theme_id, \app\helpers\ArrayHelper::map((new \app\modules\tests\models\constructor\FullTheme())->getThemes(),'id','nameWithQCount'), [
                            'class' => 'form-control chosen-select',
                            'prompt' => '<Без темы>'
                        ])?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="form-group mb-0 mt-3">
    <div class="row">
        <?php if (!$model->id) { ?>
        <div class="col col-auto">
            <button type="submit" class="pointer btn btn-success" name="continue"><i class="fa fa-plus"></i> <?=Yii::t("main","Следующий вопрос")?></button>
        </div>
        <?php } ?>
        <div class="col col-auto ml-auto">
            <a noscroll="true" href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id])?>" class="text-muted btn btn-light"><?=Yii::t("main","Отмена")?></a>
            <button type="submit" class="pointer btn btn-success" name="save"><?=Yii::t("main","Сохранить")?></button>
        </div>
    </div>
</div>

<?php \app\widgets\EForm\EForm::end(); ?>