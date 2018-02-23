<?php
$this->addTitle(Yii::t("main","Учебные планы"));
?>

<div class="action-content">

    <div class="row big-gutter">

        <div class="col-4">

            <?php $this->context->external = true;?>
            <?=$this->context->runAction('calendar')?>

        </div>

        <div class="col-8">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Учебные планы")?></h3>
                </div>
                <?php if (Yii::$app->user->can("base_teacher")) { ?>
                    <div class="col col-auto ml-auto">
                        <a href="<?=\app\helpers\OrganizationUrl::to(["/plans/add"])?>" class="btn btn-primary">
                            <?=Yii::t("main","Добавить план")?></a>
                    </div>
                <?php } ?>
            </div>

            <div class="filter-panel white-block mt-3">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень учебных планов")?></p>
                        <p class="text-muted mt-2"><?=Yii::t("main","Для создания нового плана, нажмите кнопку {add_link}, и заполните необходимые поля: период учебного плана, название и прочее.", [
                                'add_link' => "<a class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/plans/add"])."'>".Yii::t("main","Добавить план")."</a>"
                            ])?></p>
                    </div>
                </div>
            </div>

            <?php $this->context->external = true;?>
            <?=$this->context->runAction('list')?>

        </div>

    </div>


</div>


