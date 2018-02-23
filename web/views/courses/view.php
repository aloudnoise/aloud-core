<?php
$this->setTitle($model->name);
?>

<div class="action-content">
	<div class="white-block">
        <div class="row">
            <div class="col">
                <h4 class="text-info"><?=$model->name?></h4>
            </div>
            <?php if ($model->canEdit) { ?>
                <div class="col-auto ml-auto">
                    <div class="btn-group btn-group-sm">
                        <?php if ($model->state == \common\models\Courses::CREATED) { ?>
                            <a href="<?=\app\helpers\OrganizationUrl::to(["/courses/publish", "id" => $model->id])?>" class="btn btn-success"><?=Yii::t("main","Опубликовать")?></a>
                        <?php } else if ($model->state == \common\models\Courses::PUBLISHED) { ?>
                            <a href="<?=\app\helpers\OrganizationUrl::to(["/courses/unpublish", "id" => $model->id])?>" class="btn btn-warning"><?=Yii::t("main","Отозвать на доработку")?></a>
                        <?php } ?>
                        <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/courses/add", "id" => $model->id])?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=\app\helpers\OrganizationUrl::to(["/courses/delete", "type"=>1, "tid" => $model->id, "cid" => $model->id])?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if ($model->canEdit AND $model->state == \common\models\Courses::CREATED) { ?>
            <div class="alert alert-warning mt-3 mb-0"><?=Yii::t("main","Данный курс успешно создан, но еще не опубликован. После наполнения курса учебными материалами, нажмите кнопку \"Опубликовать\"")?></div>
        <?php } ?>

        <div class="mt-3 text-muted">
            <?=nl2br($model->description)?>
        </div>

        <div class=" mt-3">
            <small>
                <div class="row">
                    <div class="col-auto align-self-center">
                        <p class="text-muted"><i class="fa fa-tags"></i> <?= $model->tagsString?></p>
                    </div>
                    <div class="col-auto align-self-center">
                        <p class='text-muted'>
                            <?=Yii::t("main","Уроков: <b class='text-danger'>{l_count}</b>", [
                                "l_count" => $model->lcount
                            ])?>
                        </p>
                    </div>

                    <div class="col-auto align-self-center">
                        <p class="text-muted">
                            <?php
                            if ($model->continuous) {
                                echo '<i class="fa fa-check"> ' . Yii::t("main", "Последовательный") . '</i>';
                            } else {
                                echo '<i class="fa fa-times"> ' . Yii::t("main", "Свободный") . '</i>';
                            }
                            ?>
                        </p>
                    </div>

                    <div class="col-auto ml-auto align-self-center">
                        <p class="text-muted">
                            <i class="fa fa-eye"></i> <?=$model->viewsCount?>
                        </p>
                    </div>
                    <div class="col-auto align-self-center">
                        <p class="text-muted">
                            <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                "time" => $model->ts,
                                "formatType" => 2
                            ]) ?>
                        </p>
                    </div>

                </div>
            </small>
        </div>

    </div>

    <?php if (!empty($model->lessons)) { ?>
        <?php
        $n = 1;
        foreach ($model->lessons as $lesson) { ?>
            <?php if ($form AND Yii::$app->request->get("l_id") == $lesson->id) { ?>
                <?=$form?>
            <?php } else { ?>
                <?= $this->render("_lesson", [
                    'lesson' => $lesson,
                    'model' => $model,
                    'n' => $n
                ]) ?>
                <?php
                $n++;
            }
        }
    }
    ?>

    <?php if ($model->canEdit) { ?>
        <?php if (!Yii::$app->request->get("l_id") AND $form) { ?>
            <?=$form?>
        <?php } else { ?>
            <div class="text-center mt-4">
                <a href="<?=\app\helpers\OrganizationUrl::to(["/courses/view", 'id'=>$model->id, 'a' => 'add_lesson'])?>" class="add-question btn btn-lg btn-primary inline-block"><?=Yii::t("main","Добавить урок")?></a>
            </div>
        <?php } ?>
    <?php } ?>


</div>