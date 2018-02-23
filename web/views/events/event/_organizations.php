
<?php

?>

<?php if ($model->canEdit AND \common\models\Organizations::getCurrentOrganization()->getChildOrganizations()) { ?>
    <div class="white-block mt-4">
        <div class="row mb-3">
            <div class="col">
                <h5>
                    <?=Yii::t("main","Организации")?>
                </h5>
            </div>
            <?php if ($model->canEdit AND !$readonly) {

                $ff = [];
                if (!empty($model->organizations)) {
                    $ff = [
                        'exclude_ids' => implode(",", \app\helpers\ArrayHelper::map($model->organizations, 'related_id', 'related_id'))
                    ];
                }

                ?>
                <div class="col col-auto ml-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to([
                        '/organizations/assign',
                        'filter' => $ff,
                        "from" => $from->params,
                        'return' => Yii::$app->request->url
                    ])?>"  class="ml-2 text-primary"><i class="fa fa-user"></i> <?=Yii::t("main","Прикрепить дочернюю организацию")?></a>
                </div>
            <?php } ?>
        </div>
        <hr/>
        <?php if (!empty($model->organizations)) { ?>
            <?php foreach ($model->organizations as $organization) {
                if ($organization->organization) { ?>
                    <div class="user mt-2 pb-2 relative bordered-bottom">
                        <div class="row">
                            <div class="col">
                                <div class="col">
                                    <h6><?=$organization->organization->name?></h6>
                                </div>
                            </div>
                            <?php if ($model->canEdit AND !$readonly) { ?>
                                <div class="col col-auto ml-auto">
                                    <div class="text-right">
                                        <a href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type" => 9, "tid" => $organization->id, "eid" => $model->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить организацию?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
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
<?php } ?>