<?php
$this->setTitle($model->name);
?>

<div class="action-content">

    <div class="white-block mb-3">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h4 class="text-info"><?=$model->name?></h4>
                </div>

            <?php if ($model->canEdit) { ?>
                <div class="col-auto">
                    <div class="btn-group btn-group-sm">
                        <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/plans/add", "id" => $model->id])?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=\app\helpers\OrganizationUrl::to(["/plans/delete", "id" => $model->id])?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-auto">

                <p class="text-muted py-2">
                    <?=$model->description?>
                </p>

            </div>
            <div class="col-auto ml-auto align-self-center">
                <?=\app\widgets\EProfile\EProfile::widget([
                    'model' => $model->owner
                ])?>
            </div>

            <div class="col-12"></div>

            <div class="col-auto mt-2">
                <p class="text-muted">
                    <small>
                        <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => $model->getByFormat('begin_ts', 'd.m.Y'),
                            "showTime" => false,
                            "formatType" => 2
                        ]) ?> - <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => $model->getByFormat('end_ts', 'd.m.Y'),
                            "showTime" => false,
                            "formatType" => 2
                        ]) ?>
                    </small>
                </p>
            </div>
        </div>

    </div>

    <div class="white-block mt-3">

        <h5 class="mb-4"><?=Yii::t("main","Содержание плана")?></h5>

        <div class="white-block border-warning">
            <?php
            $f = \app\widgets\EForm\EForm::begin([
                "htmlOptions"=>[
                    "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/plans/view"], \Yii::$app->request->get(null, []))),
                    "method"=>"post",
                    "id"=>"planForm"
                ],
            ]);
            ?>

            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-3" attribute="events_planned">
                        <label for="name" class="control-label"><?=$model->getAttributeLabel("events_planned")?></label>
                        <input class="form-control" type="text" placeholder="<?=$model->getAttributeLabel("events_planned")?>" id="events_planned" value="<?=$model->events_planned?>" name="events_planned" />
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-3" attribute="pupils_planned">
                        <label for="name" class="control-label"><?=$model->getAttributeLabel("pupils_planned")?></label>
                        <input class="form-control" type="text" placeholder="<?=$model->getAttributeLabel("pupils_planned")?>" id="pupils_planned" value="<?=$model->pupils_planned?>" name="pupils_planned" />
                    </div>
                </div>
            </div>

            <div class="form-group mt-2 mb-0">
                <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />
            </div>

            <?php \app\widgets\EForm\EForm::end(); ?>
        </div>

    </div>

</div>