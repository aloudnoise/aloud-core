<?php if ($model->state != \app\models\Events::STATE_SHARED) { ?>
    <div class="white-block mt-4">
        <div class="row mb-3">
            <div class="col">
                <h5>
                    <?=Yii::t("main","Слушатели")?>
                </h5>
            </div>
            <?php if ($model->canEdit AND !$readonly) {

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
                        "from" => $from->params,
                        'return' => Yii::$app->request->url
                    ])?>"  class="ml-2 text-primary"><i class="fa fa-user"></i> <?=Yii::t("main","слушателя")?></a>
                    <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(['/hr/groups/search', "from" => $from->params])?>"  class="ml-2 text-warning"><i class="fa fa-group"></i> <?=Yii::t("main","группу")?></a>
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
                                    "readonly" => $model->isParticipant ? true : false,
                                    "type" => "media",
                                ]);
                                ?>
                            </div>
                            <?php if ($model->canEdit AND !$readonly) { ?>
                                <div class="col col-auto ml-auto">
                                    <div class="text-right">
                                        <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/hr/users/options", 'id'=>$user->related_id, 'from' => $from->params])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" title="<?=Yii::t("main","Настройки")?>" class="text-primary"><i class="fa fa-gears"></i></a>
                                        <a href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type" => 2, "tid" => $user->id, "eid" => $model->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить слушателя?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                    <div class="mt-2">
                                        <?php if ($user->criteria) { ?>
                                            <small><?=Yii::t("main","Критерий:")?> <span class="text-muted"> <?=$user->criteria?></span></small>
                                        <?php } ?>
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
                    <?php if ($model->canEdit) { ?>
                        <div class="col-auto ml-auto">
                            <a href="<?=\app\helpers\OrganizationUrl::to(['/events/share', 'id' => $model->id])?>" class="text-danger"><b><i class="fa fa-users"></i> <?=Yii::t("main","Сделать общедоступным")?></b></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>

    <div class="white-block mt-1">

        <div class="alert alert-info mb-0">
            <div class="row">
                <div class="col-auto">
                    <?=Yii::t("main","Мероприятие в общем доступе")?>
                </div>
                <?php if ($model->canEdit) { ?>
                    <div class="col-auto ml-auto">
                        <a href="<?=\app\helpers\OrganizationUrl::to(['/events/share', 'id' => $model->id])?>" class="text-danger"><b><i class="fa fa-user-times"></i> <?=Yii::t("main","Закрыть доступ")?></b></a>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>

    <?=$this->render("_organizations", [
        'model' => $model,
        'readonly' => $readonly,
        'from' => $from
    ])?>

<?php } ?>