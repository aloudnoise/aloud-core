<?php if (!empty($model->courses)  OR $model->canEdit) { ?>
    <div class="white-block mb-4">
        <div class="row">
            <div class="col">
                <h5>
                    <?=Yii::t("main","Курсы")?>
                </h5>
            </div>
            <?php if ($model->canEdit  AND !$readonly) { ?>
                <div class="col col-auto ml-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/courses/index', 'from' => $from->params, 'return' => Yii::$app->request->url])?>" class="text-success"><i class="fa fa-book"></i> <?=Yii::t("main","Прикрепить курс")?></a>
                </div>
            <?php } ?>
        </div>
        <hr />
        <?php if (!empty($model->courses)) { ?>
            <?php foreach ($model->courses as $course) { ?>
                <div class="test mt-2 pb-2 relative bordered-bottom">
                    <?php
                    echo \app\widgets\ECourse\ECourse::widget([
                        "model" => $course->course,
                        "readonly" => $readonly,
                        "backbone" => false,
                    ]);
                    ?>
                    <?php if ($model->canEdit) { ?>
                        <div style="position:absolute; right:5px; top:5px;">
                            <a href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type" => 3, "tid" => $course->id, "eid" => $model->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить курс?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
        <?php } ?>
    </div>
<?php } ?>