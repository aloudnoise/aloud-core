<?php
$this->setTitle(Yii::t("main","Просмотр материала"), false);
?>
<div class="action-content">
    <div class="white-block">
        <div class="page-header mb-3">
            <div class="row">
                <div class="col">
                    <h4><?=$material->name?></h4>
                </div>
                <div class="col-auto ml-auto">
                    <?php if (!Yii::$app->user->isGuest AND $material->canDelete) {
                        if ($material->is_deleted != 1) { ?>
                            <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=\app\helpers\OrganizationUrl::to(["/library/delete", "id"=>$material->id])?>" class="btn btn-danger btn-sm"><li class="fa fa-trash-o"></li></a>
                        <?php } } ?>
                    <?php if (!Yii::$app->user->isGuest AND $material->canEdit AND !$material->is_deleted) { ?>
                        <a href="<?=\app\helpers\OrganizationUrl::to(["/library/add", "id"=>$material->id])?>" class="btn btn-sm btn-primary " style="margin-right:5px;"><li style="padding-right:0px;" class="fa fa-pencil"></li></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if (!Yii::$app->user->isGuest AND $material->canDelete) {
            if ($material->is_deleted == 1) { ?>
                <div class="alert alert-danger">
                    <h5><?= Yii::t("main", "Данный материал удален. После обновления страницы, вы больше не сможете сюда попасть") ?></h5>
                    <a href="<?= \app\helpers\OrganizationUrl::to(["/library/restore", "id" => $material->id]) ?>"
                       class="btn-warning btn mt-3"><?= Yii::t("main", "Востановить") ?></a>
                </div>
            <?php }
        }?>

        <?php if (!Yii::$app->request->get("from") AND $material->canEdit AND $material->access_by_link) { ?>
            <div class="mt-2">
                <?php $link = \app\helpers\Url::to(['/library/shared', 'm' => $material->hash], true); ?>
                <p><?=Yii::t("main","Ссылка для прямого доступа:")?> <a href="<?=$link?>"><?=$link?></a></p>
            </div>
        <?php } ?>

        <div class="text-muted mt-2">
            <?php
               echo  $material->parsedText;
            ?>
        </div>
        <div class="material-content mt-4 mb-4">
            <?php
                echo \app\widgets\EMaterial\EMaterialInfo::widget([
                    "material" => $material
                ])
            ?>
        </div>

        <?php if ($material->source) { ?>
            <div class="text-content">
                <span class='text-primary'><?=Yii::t("main","Источник")?>:</span> <?=$material->source?>
            </div>
        <?php } ?>

        <div class="item-stats mt-3">
            <div class="row">
                <div class="col-auto">
                    <div class="display-date"><?php echo \app\widgets\EDisplayDate\EDisplayDate::widget(["time"=>$material->ts]); ?></div>
                </div>
                <div class="col-auto">
                    <div class="material-likes-widget"></div>
                </div>
                <div class="col-auto">
                    <div title="<?=Yii::t("main","Просмотров")?>" class="count-view"><i class="fa fa-eye"></i> <?=$material->viewsCount?></div>
                </div>
                <div class="col-auto">
                    <div title="<?=Yii::t("main","Скачиваний")?>" class="count-download"><i class="fa fa-cloud-download"></i> <?=$material->downloadsCount?></div>
                </div>
            </div>
        </div>
    </div>
</div>