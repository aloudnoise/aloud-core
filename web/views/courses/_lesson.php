<?php

    $from = new \common\models\From(['lesson', $lesson->id, 'assign']);
    $process = new \common\models\From(['lesson',$lesson->id,'process']);

?>

<div class="white-block mt-3">
    <h5 class="mb-2 text-purple"><?= $n ?> . <?= $lesson->name ?>
        <?php if ($model->canEdit) { ?>
            <div class="pull-right">
                <a style="font-size:16px;"
                   title="<?= Yii::t("main", "Редактировать") ?>"
                   href="<?= \app\helpers\OrganizationUrl::to(["/courses/view", "id" => $lesson->course_id, "a" => "add_lesson", "l_id" => $lesson->id]) ?>"
                   class="text-primary"><i class="fa fa-pencil"></i></a>
                <a href="<?= \app\helpers\OrganizationUrl::to(["/courses/delete", "type" => 2, "tid" => $lesson->id, "cid" => $lesson->course_id]) ?>"
                   style="cursor:pointer; font-size:16px; margin-left:5px;"
                   confirm='<?= Yii::t("main", "Вы уверены, что хотите удалить урок?") ?>'
                   title="<?= Yii::t("main", "Удалить") ?>" class="text-warning"><i
                        class="fa fa-trash-o"></i></a>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
    </h5>

    <p><?= $lesson->content ?></p>

</div>

<div class="mt-2">
    <div class="row  align-items-stretch">
        <?php if (!empty($lesson->materials) OR $model->canEdit) { ?>
            <div class="col align-self-stretch">
                <div class="white-block" style="height:100%;">
                    <div class="row">
                        <div class="col">
                            <span><?= Yii::t("main", "Материалы") ?></span>
                        </div>
                        <?php if ($model->canEdit) { ?>
                            <div class="col-auto ml-auto">
                                <a href="<?= \app\helpers\OrganizationUrl::to([
                                    '/library/index',
                                    'from' => $from->params,
                                    'return' => Yii::$app->request->url
                                ]) ?>"
                                   class="text-success"><i class="fa fa-book"></i> <?= Yii::t("main", "Прикрепить материал") ?></a>
                            </div>
                        <?php } ?>
                    </div>
                    <hr />

                    <?php if (!empty($lesson->materials)) { ?>
                        <?php foreach ($lesson->materials as $clm) { ?>
                            <?php if ($clm->material) { ?>
                                <div class="mb-2 relative">
                                    <?php
                                    echo \app\widgets\EMaterial\EMaterial::widget([
                                        "model" => $clm->material,
                                        "backbone" => false,
                                        "type" => "media",
                                        "from" => $process
                                    ]);
                                    ?>
                                    <?php if ($model->canEdit) { ?>
                                        <div style="position:absolute; right:5px; top:5px;">
                                            <a href="<?= \app\helpers\OrganizationUrl::to(["/courses/delete", "type" => 3, "tid" => $clm->id, "cid" => $lesson->course_id]) ?>"
                                               style="cursor:pointer; font-size:16px; margin-left:5px;"
                                               confirm='<?= Yii::t("main", "Вы уверены, что хотите удалить материал?") ?>'
                                               title="<?= Yii::t("main", "Удалить") ?>"
                                               class="text-danger"><i
                                                    class="fa fa-trash-o"></i></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if (!empty($lesson->tests) OR $model->canEdit) { ?>
        <div class="col align-self-stretch">
            <div class="white-block" style="height:100%;">
                <div class="row">
                    <div class="col">
                        <span><?= Yii::t("main", "Тесты") ?></span>
                    </div>
                    <?php if ($model->canEdit) { ?>
                        <div class="col col-auto ml-left">
                            <a href="<?= \app\helpers\OrganizationUrl::to([
                                '/tests/base/index',
                                'from' => $from->params,
                                'return' => Yii::$app->request->url
                            ]) ?>"
                               class="text-success"><i class="fa fa-list"></i> <?= Yii::t("main", "Прикрепить тест") ?></a>
                        </div>
                    <?php } ?>
                </div>
                <hr />

                <?php if (!empty($lesson->tests)) { ?>
                    <?php foreach ($lesson->tests as $clt) { ?>
                        <?php if ($clt->test) { ?>
                            <div class="mb-2 relative">
                                <?php
                                echo \app\widgets\ETest\ETest::widget([
                                    "model" => $clt->test,
                                    "from" => $process,
                                    "backbone" => false
                                ]);
                                ?>
                                <?php if ($model->canEdit) { ?>
                                    <div style="position:absolute; right:5px; top:5px;">
                                        <a href="<?= \app\helpers\OrganizationUrl::to(["/courses/delete", "type" => 4, "tid" => $clt->id, "cid" => $lesson->course_id]) ?>"
                                           style="cursor:pointer; font-size:16px; margin-left:5px;"
                                           confirm='<?= Yii::t("main", "Вы уверены, что хотите удалить тест?") ?>'
                                           title="<?= Yii::t("main", "Удалить") ?>"
                                           class="text-danger"><i
                                                class="fa fa-trash-o"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

        <?php if (!empty($lesson->tasks) OR $model->canEdit) { ?>
        <div class="col align-self-stretch">
            <div class="white-block" style="height:100%;">
                <div class="row">
                    <div class="col">
                        <span><?= Yii::t("main", "Задание") ?></span>
                    </div>
                    <?php if ($model->canEdit && !$lesson->tasks) { ?>
                        <div class="col col-auto ml-auto">
                            <a href="<?= \app\helpers\OrganizationUrl::to([
                                '/tasks/index',
                                'from' => $from->params,
                                'return' => Yii::$app->request->url
                            ]) ?>"
                               class="text-success"><i class="fa fa-tasks"></i> <?= Yii::t("main", "Прикрепить задание") ?></a>
                        </div>
                    <?php } ?>
                </div>
                <hr />
                <?php if (!empty($lesson->tasks)) { ?>
                    <?php foreach ($lesson->tasks as $clt) { ?>
                        <?php if ($clt->task) { ?>
                            <div class="mb-2 relative">
                                <?php
                                echo \app\widgets\ETask\ETask::widget([
                                    "model" => $clt->task,
                                    "from" => $process,
                                    "canEdit" => $model->canEdit,
                                    "backbone" => false,
                                ]);
                                ?>
                                <?php if ($model->canEdit) { ?>
                                    <div style="position:absolute; right:5px; top:5px;">
                                        <a href="<?= \app\helpers\OrganizationUrl::to([
                                            "/courses/delete",
                                            "type" => 5,
                                            "tid" => $clt->id,
                                            "cid" => $lesson->course_id
                                        ]) ?>"
                                           style="cursor:pointer; font-size:16px; margin-left:5px;"
                                           confirm='<?= Yii::t("main", "Вы уверены, что хотите удалить задание?") ?>'
                                           title="<?= Yii::t("main", "Удалить") ?>"
                                           class="text-danger"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                <?php } ?>

                                <?php if ($model->canEdit) { ?>
                                    <div class="row mt-1">
                                        <div class="col-auto ml-auto">
                                            <a href="<?=\app\helpers\OrganizationUrl::to([
                                                "/tasks/check",
                                                "id" => $clt->task->id,
                                                "from" => (new \common\models\From([$from->name, $from->id,'check']))->params
                                            ])?>" class="text-warning">
                                                <i class="fa fa-pencil-square-o"></i> <?=Yii::t("main","Проверить решения")?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>




                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>