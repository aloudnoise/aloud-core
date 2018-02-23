<?php
$this->setTitle($model->name);
?>

<div class="action-content">

    <div class="white-block mb-4">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h4 class="text-info"><?=$model->name?></h4>
                </div>

            <?php if ($model->canEdit) { ?>
                <div class="col-auto ml-auto">
                    <p>
                        <a href="<?=\app\helpers\OrganizationUrl::to(['/reports/index',  'filter' => ['type' => 'tests'], 'from' => (new \common\models\From(['event', $model->id, 'report']))->getParams()])?>" class="btn btn-outline-info btn-sm"><?=Yii::t("main","Отчеты по мероприятию")?></a>
                    </p>
                </div>
                <div class="col-auto">
                    <div class="btn-group btn-group-sm">
                        <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/events/add", "id" => $model->id])?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type"=>1, "tid" => $model->id, "eid" => $model->id])?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
            <?php if ($model->education_view) { ?>
                <div class="col-auto mt-2">
                    <p class="text-muted">
                        <small><?=\app\models\DicValues::fromDic($model->education_view)?></small>
                    </p>
                </div>
            <?php } ?>

        </div>

    </div>

    <?php
        $readonly = false;
        $process = new \common\models\From(['event',$model->id,'process']);
    ?>

    <?php if ((!$model->canEdit AND $model->participation['status'] !== 1) OR ($model->participation['status'] == 3)) { ?>
        <?php $readonly = true; ?>
        <div class="white-block mb-4">
            <div class="m-0 alert alert-<?=$model->participation['color']?>"><h4><?=$model->participation['text']?></h4></div>
        </div>
    <?php } else if (!(!$model->canEdit AND $model->participation['status'] == 2)) { ?>
    <div class="row big-gutter">

            <div class="col col-6">

                <?=$this->render("event/_themes", [
                    'model' => $model,
                    'readonly' => $readonly,
                    'from' => $from
                ])?>

                <?=$this->render("event/_pupils", [
                    'model' => $model,
                    'readonly' => $readonly,
                    'from' => $from
                ])?>
            </div>


        <div class="col-6">
            <?=$this->render("event/_courses", [
                'model' => $model,
                'readonly' => $readonly,
                'from' => $from
            ])?>

            <?=$this->render("event/_materials", [
                'model' => $model,
                'readonly' => $readonly,
                'from' => $from,
                'process' => $process
            ])?>

            <?=$this->render("event/_tests", [
                'model' => $model,
                'readonly' => $readonly,
                'from' => $from,
                'process' => $process
            ])?>

            <?=$this->render("event/_tasks", [
                'model' => $model,
                'readonly' => $readonly,
                'from' => $from,
                'process' => $process
            ])?>

        </div>
    </div>
    <?php } ?>

</div>