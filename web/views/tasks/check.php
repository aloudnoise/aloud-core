<div class="action-content">
    <?php
    $this->setTitle($task->name);
    ?>

    <div class="white-block">
        <div class="row">
            <div class="col-auto align-self-center">
                <h5 class="pull-left text-info">
                    <?=$task->name?>
                </h5>
            </div>
        </div>

        <div class="p-5 text-muted">
            <?=$task->content?>
        </div>

        <div class="mt-2">
            <div class="row">
                <div class="col-auto">
                    <p class="text-muted">
                        <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time" => $task->ts,
                            "formatType" => 2
                        ]) ?>
                    </p>
                </div>
                <div class="col-auto">
                    <p class="text-muted"><?= Yii::t("main","Время: ")?> <?=$task->time?>м.</p>
                </div>
            </div>
        </div>

    </div>

    <div class="white-block mt-3">
        <h5><?=Yii::t("main","Ответы слушателей")?></h5>
    </div>

    <?php if ($models) { ?>
    <?php foreach ($models as $model) { ?>
        <div class="task-result-item white-block mt-1" task_id="<?=$model->id?>">
            <div class="row">
                <div class="col">
                    <?=\app\widgets\EProfile\EProfile::widget([
                            'model' => $model->user
                    ])?>
                </div>
            </div>

            <div class="p-4 text-muted"><?= $model->answer ?></div>

            <?php if (!$model->result) { ?>

                <div class="alert alert-info"><?=Yii::t("main","Оценка не выставлена")?></div>

                <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(['/tasks/estimate', 'id' => $model->id])?>" class="btn btn-success"><?=Yii::t("main","Оценить")?></a>

            <?php } else { ?>
                <div class="row mt-1">
                    <div class="col col-auto align-self-end">
                        <h4 style="margin-bottom:2px;"><?=Yii::t("main","Оценка учителя:")?></h4>
                    </div>
                    <div class="col col-auto">
                        <h1 class="ml-3 text-<?=$model->resultTextColor?>"><?=$model->translatedResultText?></h1>
                    </div>
                    <div class="col-auto ml-auto">
                        <?=\app\widgets\EProfile\EProfile::widget([
                            'model' => $model->reviewer
                        ])?>
                    </div>
                </div>

                <?php if ($model->note) { ?>
                    <div class="mt-3">
                        <p class="mb-2"><?=Yii::t("main","Пояснение учителя")?></p>
                        <p><?=$model->note?></p>
                    </div>
                <?php } ?>
            <?php } ?>

            <div class="mt-4">
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

        </div>
    <? } ?>
    <?php } else { ?>
        <div class="white-block mt-1">
            <div class="alert alert-warning mb-0"><?=Yii::t("main","Слушатели еще не отправили ни одного решения")?></div>
        </div>
    <?php } ?>
</div>
