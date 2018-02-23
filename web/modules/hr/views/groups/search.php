<?php
$this->setTitle(Yii::t("main","Группы"), false);
?>
<div class="action-content">

    <div class="form-group">
        <?php if ($provider OR (!$provider AND $filter->search)) { ?>
        <div class="row">
            <div class="col-8" style="padding-right:3px;">
                <input placeholder="<?=Yii::t("main","Поиск групп по названию")?>" type="text" value="<?=$filter->search?>" class="find-input form-control autocomplete" autocomplete-attribute="name" />
            </div>
            <div class="col-1" style="padding-left:0;">
                <a class="find-button btn btn-outline-dark pointer"><?=Yii::t("main","Найти")?></a>
            </div>
        </div>
        <?php } else { ?>
            <div class="row justify-content-center" style="margin-top:70px; margin-bottom:70px;">
                <div class="col-10">
                    <input placeholder="<?=Yii::t("main","Поиск групп по названию")?>" type="text" value="<?=$filter->search?>" class="form-control-lg find-input form-control autocomplete" autocomplete-attribute="name" />
                </div>
                <div class="col-10" style="margin-top:15px;">
                    <a href="#" class="find-button btn btn-outline-primary btn-lg pointer"><?=Yii::t("main","Найти")?></a>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if ($provider AND $provider->totalCount > 0) { ?>

        <?php
            $models = $provider->getModels();
        ?>

        <?php if ($models) { ?>
            <div class="groups-container">
                <?php foreach ($models as $group) { ?>
                    <div class="assign-item list-item" assign_item="group" assign_id="<?=$group->id?>">
                        <?= \app\widgets\EProfile\EGroupProfile::widget([
                            "model" => $group,
                            "type" => "media",
                        ]); ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?= \app\widgets\EPager\EPager::widget([
            'pagination' => $provider->pagination,
        ]) ?>

    <?php } else if ($provider AND $provider->totalCount == 0) { ?>

        <div class="alert alert-danger"><?=Yii::t("main","Групп не найдено")?></div>

    <?php } ?>



</div>