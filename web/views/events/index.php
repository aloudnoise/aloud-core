<?php
$this->addTitle(Yii::t("main","Мероприятия"));
?>

<div class="action-content">

    <div class="row big-gutter">

        <div class="col-md-5 col-lg-5 col-xl-4">

            <?php $this->context->external = true;?>
            <?=$this->context->runAction('calendar')?>

            <div class="white-block mt-3">

                <?=$this->render("@app/views/common/tags", [
                    'tags' => $tags,
                    'route' => '/events/index'
                ])?>

            </div>

        </div>

        <div class="col-md-7 col-lg-7 col-xl-8">

            <div class="row">
                <div class="col">
                    <h3><?=Yii::t("main","Мероприятия")?></h3>
                </div>
                <?php if (Yii::$app->user->can("specialist")) { ?>
                    <div class="col-auto ml-auto">
                        <a href="<?=\app\helpers\OrganizationUrl::to(["/plans/index"])?>" class="btn-sm btn btn-outline-info">
                            <?=Yii::t("main","Учебные планы")?></a>
                    </div>
                <?php } ?>
                <?php if (Yii::$app->user->can("base_teacher")) { ?>
                    <div class="col-auto <?=Yii::$app->user->can("specialist") ? "" : "ml-auto"?>">
                        <a href="<?=\app\helpers\OrganizationUrl::to(["/events/add"])?>" class="btn-sm btn btn-primary">
                            <?=Yii::t("main","Добавить мероприятие")?></a>
                    </div>
                <?php } ?>
            </div>

            <div class="filter-panel white-block mt-3">
                <div class="row">
                    <div class="col-12">
                        <?php if (\Yii::$app->user->can("teacher")) { ?>
                            <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень мероприятий")?></p>
                            <p class="lh-1 text-muted mt-2"><?=Yii::t("main","Для создания нового мероприятия, нажмите кнопку {add_link}, и заполните необходимые поля: дату проведения мероприятия, название и прочее. К каждому мероприятию вы можете добавить курсы, тесты и материалы", [
                                    'add_link' => "<a class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/events/add"])."'>".Yii::t("main","Добавить мероприятие")."</a>"
                                ])?></p>
                        <?php } else { ?>
                            <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть мероприятия, в которых вы участвуете")?></p>
                            <p class="lh-1 text-muted mt-2"><?=Yii::t("main","Чтобы посмотреть содержание мероприятия, кликните по названию мероприятия. Внутри мероприятия вы сможете просматривать курсы, материалы, а также проходить тестирования.")?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php $this->context->external = true;?>
            <?=$this->context->runAction('list')?>

        </div>

    </div>


</div>


