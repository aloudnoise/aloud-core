<?php
$this->setTitle(Yii::t("main","Пользователи"), false);
?>
<div class="action-content">

    <div class="form-group">
        <?php if ($users OR (!$users AND $filter->search)) { ?>
        <div class="row">
            <div class="col-8" style="padding-right:3px;">
                <input placeholder="<?=Yii::t("main","Поиск пользователей по фио")?>" type="text" value="<?=$filter->search?>" class="find-input form-control autocomplete" autocomplete-attribute="name" />
            </div>
            <div class="col-1" style="padding-left:0;">
                <a class="find-button btn btn-outline-dark pointer"><?=Yii::t("main","Найти")?></a>
            </div>
        </div>
        <?php } else { ?>
            <div class="row justify-content-center" style="margin-top:70px; margin-bottom:70px;">
                <div class="col-10">
                    <input placeholder="<?=Yii::t("main","Поиск пользователей по фио")?>" type="text" value="<?=$filter->search?>" class="form-control-lg find-input form-control autocomplete" autocomplete-attribute="name" />
                </div>
                <div class="col-10" style="margin-top:15px;">
                    <a class="find-button btn btn-outline-dark btn-lg pointer"><?=Yii::t("main","Найти")?></a>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if ($provider) { ?>
        <div class="users-container">
            <?php $users = $provider->getModels() ?>
            <?php if ($users) { ?>
                <?php foreach ($users as $user) { ?>
                    <div class="assign-item list-item mb-3" assign_item="user" assign_id="<?=$user->user->id?>">
                        <?= \app\widgets\EProfile\EProfile::widget([
                            "model" => $user->user,
                            "type" => "media",
                        ]); ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <?= \app\widgets\EPager\EPager::widget([
            'pagination' => $provider->pagination,
        ]) ?>

    <?php } else if (!$users AND $filter->search) { ?>

        <div class="alert alert-danger"><?=Yii::t("main","Пользователей не найдено")?></div>

    <?php } ?>



</div>