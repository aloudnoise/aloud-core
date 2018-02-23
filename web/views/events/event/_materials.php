<?php if (!empty($model->materials)  OR $model->canEdit) { ?>
    <div class="white-block mb-4">
        <div class="row">
            <div class="col">
                <h5>
                    <?=Yii::t("main","Материалы")?>
                </h5>
            </div>
            <?php if ($model->canEdit AND !$readonly) { ?>
                <div class="col col-auto ml-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/library/index', 'from' => $from->params, 'return' => Yii::$app->request->url])?>" class="text-success"><i class="fa fa-folder-open"></i> <?=Yii::t("main","Прикрепить материал")?></a>
                </div>
            <?php } ?>
        </div>
        <hr />
        <?php if (!empty($model->materials)) { ?>
            <?php foreach ($model->materials as $material) { ?>
                <?php if ($material->material) { ?>
                    <div class="material mt-2 pb-2 relative bordered-bottom">
                        <?php
                        echo \app\widgets\EMaterial\EMaterial::widget([
                            "model" => $material->material,
                            "backbone" => false,
                            'readonly' => $readonly,
                            "type" => "media",
                            "from" => $process
                        ]);
                        ?>
                        <?php if ($model->canEdit AND !$readonly) { ?>
                            <div style="position:absolute; right:5px; top:5px;">
                                <a href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type" => 5, "tid" => $material->id, "eid" => $model->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить материал?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
        <?php } ?>
    </div>
<?php } ?>