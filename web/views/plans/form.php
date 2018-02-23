<?php
$this->addTitle( (\Yii::$app->request->get('id') )?(Yii::t("main","Редактирование плана")):(Yii::t("main","Добавление плана")));
?>

<div class="action-content">

    <div class="row">

        <?php if (!$this->context->isModal) { ?>
            <div class="col-4">

                <div class="white-block pl-5 pr-5 pb-4 border-warning">

                    <?php $this->context->external = true;?>
                    <?=$this->context->runAction('calendar')?>

                </div>

                <?php $this->context->external = true;?>
                <?=$this->context->runAction('list')?>

                <?php $this->context->external = false; ?>

            </div>
        <?php } ?>

        <div class="col">

            <div class="white-block border-warning">
                <?php
                $f = \app\widgets\EForm\EForm::begin([
                    "htmlOptions"=>[
                        "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/plans/add"], \Yii::$app->request->get(null, []))),
                        "method"=>"post",
                        "id"=>"newPlanForm"
                    ],
                ]);
                ?>

                <?php if ($model->isNewRecord) { ?>
                    <div class="page-header mb-3"><h5><?=Yii::t("main","Новый план")?></h5></div>
                <?php } ?>

                <div class="form-group mb-3" attribute="name">

                    <label for="name" class="control-label"><?=$model->getAttributeLabel("name")?></label>
                    <input class="form-control" type="text" placeholder="<?=$model->getAttributeLabel("name")?>" id="name" value="<?=$model->name?>" name="name" />

                </div>

                <div class="form-group mb-3" attribute="begin_ts">
                    <label for="begin_ts_display" class="control-label"><?=$model->getAttributeLabel("begin_ts_display")?></label>
                    <input fixed="true" class="form-control date" style="width:150px" autocomplete="off" name="begin_ts" id="begin_ts" value="<?=$model->begin_ts_display?>" type="text">
                </div>
                <div class="form-group mb-3" attribute="end_ts">
                    <label for="end_ts_display" class="control-label"><?=$model->getAttributeLabel("end_ts_display")?></label>
                    <input fixed="true" class="form-control date" style="width:150px" autocomplete="off" name="end_ts" id="end_ts" value="<?=$model->end_ts_display?>" type="text">
                </div>

                <div class="form-group mb-3"  attribute="description">
                    <textarea name="description" class="form-control" placeholder="<?=Yii::t("main","Введите описание плана")?>"><?=$model->description?></textarea>
                </div>

                <div class="form-group mt-5 mb-0">

                    <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />
                    <?php if ($model->isNewRecord) { ?>
                        <a class="btn btn-outline-danger" href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/plans/index'], Yii::$app->request->get()))?>"><?=Yii::t("main","Отмена")?></a>
                    <?php } ?>

                </div>

                <?php \app\widgets\EForm\EForm::end(); ?>
            </div>



        </div>


    </div>

</div>









