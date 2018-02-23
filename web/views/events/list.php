<div class="events-list mt-3">
    <?php if ($events) {
        foreach ($events as $ev) { ?>
            <div class="white-block list-item mb-3">
                <div class="row mb-2">
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <a href="<?=\app\helpers\OrganizationUrl::to(["/events/view", "id" => $ev->id])?>" class="icon-circle img-icon icon-circle-lg bg-warning"><i class="icon-2"></i></a>
                            </div>
                            <div class="col pl-3 align-self-center">
                                <h4><a href="<?=\app\helpers\OrganizationUrl::to(["/events/view", "id" => $ev->id])?>"><?php echo $ev->name ?></a></h4>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <?php if ($ev->participation['status'] != \common\models\Events::PARTICIPATION_STATUS_ACTIVE) { ?>
                                <div class="col-auto">
                                    <label style="font-weight: normal;" class="badge text-white badge-<?=$ev->participation['color']?>"><?=$ev->participation['text']?></label>
                                </div>
                            <?php } ?>
                            <?php if ($ev->education_view) { ?>
                                <div class="col-auto">
                                    <p class="date text-muted">
                                        <small><?=\app\models\DicValues::fromDic($ev->education_view)?></small>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php if ($ev->description) { ?>
                    <div class="mb-2">
                        <p class="text-muted"><?=$ev->description?></p>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-auto">
                        <p class="date text-muted"><small><?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $ev->getByFormat('begin_ts', 'd.m.Y'),
                                    "formatType" => 2,
                                    "showTime" => false,
                                ]) ?> - <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                                    "time" => $ev->getByFormat('end_ts', 'd.m.Y'),
                                    "formatType" => 2,
                                    "showTime" => false,
                                ]) ?></small></p>
                    </div>
                    <div class="col">
                        <p class="text-muted"><small><i class="fa fa-tags"></i> <?=$ev->tagsString?></small></p>
                    </div>
                    <div class="align-self-center col-auto ml-auto">
                        <div class="row">
                            <div title="<?=Yii::t("main","Курсы")?>" class="text-very-light-gray col col-auto ml-auto">
                                <i class="fa fa-book"></i> <?=intval(count($ev->courses))?>
                            </div>
                            <div title="<?=Yii::t("main","Материалы")?>" class="text-very-light-gray col col-auto ml-3">
                                <i class="fa fa-folder-open"></i><?=intval(count($ev->materials))?>
                            </div>
                            <div title="<?=Yii::t("main","Тесты")?>" class="text-very-light-gray col col-auto ml-3">
                                <i class="fa fa-list"></i> <?=intval(count($ev->tests))?>
                            </div>
                            <div title="<?=Yii::t("main","Задания")?>" class="text-very-light-gray col col-auto ml-3">
                                <i class="fa fa-file"></i> <?=intval(count($ev->tasks))?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php
        }
    } ?>
</div>