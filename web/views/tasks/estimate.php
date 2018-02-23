<?php
$this->addTitle(Yii::t("main", "Оценить"));
?>
<div class="action-content">

    <div class="white-block">
        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions" => [
                "action" => \app\helpers\OrganizationUrl::to(array_merge(["/tasks/estimate"], \Yii::$app->request->get())),
                "method" => "post",
                "id" => "newEstimateForm"
            ],
        ]);
        ?>

        <div>
            <div class="row">
                <div class="col">
                    <?=\app\widgets\EProfile\EProfile::widget([
                        'model' => $model->user
                    ])?>
                </div>
            </div>

            <div class="p-4 text-muted"><?= $model->answer ?></div>
        </div>

        <div class="mt-2">
            <div class="row">
                <div class="col-auto">
                    <p class="text-muted">
                        <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => $model->ts,
                            "formatType" => 2
                        ]) ?>
                    </p>
                </div>
                <div class="col-auto">
                    <p class="text-muted"><?= Yii::t("main","Время прохождения")?>: <?=$model->processTime?></p>
                </div>
            </div>
        </div>

        <div class="form-group mt-3" attribute="result">
            <label class="control-label"><?=$model->getAttributeLabel("result")?></label>
            <input class="form-control" name="result" value="<?=$model->result?>" placeholder="<?=$model->getAttributeLabel("result")?>" />
            <p class="mt-2 help-block text-muted"><?=Yii::t("main","Оцените ответ в % от 0 до 100")?></p>
        </div>

        <div class="form-group" attribute="note">
            <label class="control-label"><?=$model->getAttributeLabel("note")?></label>
            <?=\app\helpers\Html::textarea("note", $model->note, [
                'id' => 'content',
                "class"=>"form-control",
                "placeholder"=>$model->getAttributeLabel("note"),
                'rows' => 4
            ])?>
        </div>

        <div class="form-group mb-0">
            <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />
        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>

</div>