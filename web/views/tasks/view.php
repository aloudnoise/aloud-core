<?php
$this->setTitle(Yii::t("main", "Задание"));
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
?>

<div class="action-content">

    <div class="white-block">
        <div class="row">
            <div class="col-auto align-self-center">
                <h5 class="pull-left text-info">
                    <?=$model->name?>
                </h5>
            </div>
            <?php if ($model->canEdit) { ?>
                <div class="col-auto ml-auto">
                    <div class="btn-group btn-group-sm">
                        <a href="<?=app\helpers\OrganizationUrl::to(["/tasks/add", "id" => $model->id])?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=app\helpers\OrganizationUrl::to(["/tasks/delete", "id" => $model->id])?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="p-5 text-muted">
            <?=$model->content?>
        </div>

        <?php if ($model->files) { ?>

            <div class="mt-1 mb-3">
                <h5 class="mb-3"><?=Yii::t("main","Прикрепленные документы")?></h5>
                <?php foreach ($model->files as $file) { ?>
                    <div class="alert alert-secondary mb-1">
                        <a target="_blank" href="<?=$file['url']?>"><?=$file['name']?></a>
                    </div>
                <?php } ?>
            </div>

        <?php } ?>

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
                    <p class="text-muted"><?= Yii::t("main","Время: ")?> <?=$model->time?>м.</p>
                </div>
            </div>
        </div>

    </div>


</div>