<?php
$this->setTitle(Yii::t("main","Просмотр материала"), false);
?>
<div class="action-content">

    <div class="mb-3">
        <div class="row">
            <div class="col">
                <h2><?=$material->name?></h2>
            </div>
        </div>
    </div>

    <div class="white-block">
        <div class="text-muted">
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