<?php
$this->setTitle($model->name);
?>

<div class="action-content">

    <div class="white-block mb-1">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h4 class="text-info"><?=$model->name?></h4>
                </div>

                <?php if ($model->canEdit) { ?>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm">
                            <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/hr/groups/add", "id" => $model->id])?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                            <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=\app\helpers\OrganizationUrl::to(["/hr/groups/delete", "id" => $model->id])?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
                            "time" => $model->getByFormat('ts', 'd.m.Y'),
                            "showTime" => false,
                            "formatType" => 2
                        ]) ?>
                    </small>
                </p>
            </div>
        </div>

    </div>

    <?php
    $from = new \common\models\From(['group',$model->id,'assign']);
    ?>

    <div class="row">

        <div class="col">
            <div class="white-block mt-1">
                <div class="row mb-3">
                    <div class="col">
                        <h5>
                            <?=Yii::t("main","Слушатели")?>
                        </h5>
                    </div>
                    <?php if ($model->canEdit) {

                        $ff = [];
                        if (!empty($model->users)) {
                            $ff = [
                                'exclude_ids' => implode(",", \app\helpers\ArrayHelper::map($model->users, 'related_id', 'related_id'))
                            ];
                        }

                        ?>
                        <div class="col col-auto ml-auto">
                            <span class=""><?=Yii::t("main","Прикрепить")?></span>
                            <a href="<?=\app\helpers\OrganizationUrl::to([
                                '/hr/users/assign',
                                'filter' => $ff,
                                "from" => $from->params
                            ])?>"  class="ml-2 text-primary"><i class="fa fa-user"></i> <?=Yii::t("main","слушателя")?></a>
                        </div>
                    <?php } ?>
                </div>
                <hr/>
                <?php if (!empty($model->users)) { ?>
                    <?php foreach ($model->users as $user) {
                        if ($user->user) { ?>
                            <div class="user mt-2 pb-2 relative bordered-bottom">
                                <div class="row">
                                    <div class="col">
                                        <?php
                                        echo \app\widgets\EProfile\EProfile::widget([
                                            "model" => $user->user,
                                            "type" => "media",
                                        ]);
                                        ?>
                                    </div>
                                    <?php if ($model->canEdit) { ?>
                                        <div class="col col-auto ml-auto">
                                            <div class="text-right">
                                                <a href="<?=\app\helpers\OrganizationUrl::to(["/hr/groups/delete-user", "id" => $user->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить слушателя?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-warning mb-0">
                        <div class="row">
                            <div class="col-auto">
                                <?=Yii::t("main","Не назначено")?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>

</div>