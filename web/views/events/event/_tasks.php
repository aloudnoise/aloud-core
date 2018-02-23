<?php if (!empty($model->tasks)  OR $model->canEdit) { ?>
    <div class="white-block mb-1">
        <div class="row">
            <div class="col">
                <h5>
                    <?=Yii::t("main","Задания")?>
                </h5>
            </div>
            <?php if ($model->canEdit AND !$readonly) { ?>
                <div class="col col-auto ml-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/tasks/index', 'from' => $from->params, 'return' => Yii::$app->request->url])?>" class="text-success"><i class="fa fa-file"></i> <?=Yii::t("main","Прикрепить задание")?></a>
                </div>
            <?php } ?>
        </div>
        <hr />
        <?php
        $process = new \common\models\From(['event',$model->id,'process']);
        if (!empty($model->tasks)) { ?>
            <?php foreach ($model->tasks as $task) { ?>
                <div class="test mt-2 pb-2 relative bordered-bottom">
                    <div class="row">
                        <div class="col">
                            <?php
                            echo \app\widgets\ETask\ETask::widget([
                                "model" => $task->task,
                                "readonly" => !$readonly ? ($model->isParticipant ? false : true) : true,
                                "backbone" => false,
                                "from" => $process
                            ]);
                            ?>
                        </div>
                        <?php if ($model->canEdit AND !$readonly) { ?>
                            <div class="col col-auto ml-auto">
                                <div class="text-right">
                                    <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/tasks/options", 'id'=>$task->related_id, 'from' => $from->params])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" title="<?=Yii::t("main","Настройки")?>" class="text-primary"><i class="fa fa-gears"></i></a>
                                    <a href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type" => 7, "tid" => $task->id, "eid" => $model->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить тест?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-auto ml-auto">
                            <?php if ($task->criteria) { ?>
                                <small><?=Yii::t("main","Критерий:")?> <span class="text-muted"><?=$task->criteria?></span></small>
                            <?php } ?>
                            <?php if ($task->password) { ?>
                                <small class="text-success"><?=Yii::t("main","Пароль")?></small>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                    $check_from = new \common\models\From(['event',$model->id,'check']);
                    if ($model->canEdit AND !$readonly) { ?>
                        <div class="row mt-1">
                            <div class="col-auto ml-auto">
                                <a href="<?=\app\helpers\OrganizationUrl::to([
                                    "/tasks/check",
                                    "id" => $task->task->id,
                                    "from" => $check_from->params
                                ])?>" class="text-warning">
                                    <i class="fa fa-pencil-square-o"></i> <?=Yii::t("main","Проверить решения")?>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
        <?php } ?>
    </div>
<?php } ?>