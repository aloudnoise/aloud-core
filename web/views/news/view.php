<?php
/** @var \app\models\News $model */
$this->setTitle($model->name);
?>
<div class="action-content">
    <div class="white-block mb-1">
        <div class="page-header">
            <div class="row">
                <div class="col align-self-end">
                    <h5><?= $model->name ?></h5>
                </div>
                <?php if ($model->canEdit) { ?>
                    <div class="col col-auto ml-auto align-self-center">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= \app\helpers\OrganizationUrl::to(["/news/add", "id" => $model->id]) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                <a confirm="<?= Yii::t("main", "Вы уверены?") ?>" href="<?= \app\helpers\OrganizationUrl::to(["/news/delete", "id" => $model->id]) ?>"
                                   class="btn btn-danger"><i class="fa fa-trash"></i></a>
                            </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>

    <div class="white-block mt-2">
        <?= $model->content ?>

        <div class="row mt-3">
            <div class="col-auto ml-auto">
                <?= \app\widgets\EProfile\EProfile::widget([
                    'model' => $model->author
                ]) ?>
            </div>
        </div>

    </div>

    <div class="white-block mt-1">
        <div class="row">
            <div class="col col-auto text-muted">
                <i class="fa fa-tags"></i> <?= $model->tagsString ?>
            </div>
            <div class="col col-auto text-muted">
                <i class="fa fa-eye"></i> <?= $model->viewsCount ?>
            </div>
            <div class="col col-auto ml-auto">
                <p class="text-muted"><?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time" => $model->getByFormat('ts', 'd.m.Y'),
                        "showTime" => false,
                        "formatType" => 2
                    ]) ?></p>

            </div>
        </div>
    </div>


</div>