<?php
$this->setTitle(Yii::t("main","Вопросы"));
?>
<div class="action-content">

	<div class="white-block filter-panel mb-3">

        <ul class="nav nav-pills pull-left">
            <ul class="nav nav-tabs pull-left" style="margin-bottom:15px;">
                <?php
                $get = \Yii::$app->request->get();
                $get['filter']['pr'] = 1;
                ?>
                <li class="nav-item"><a class="nav-link <?=$this->context->id == "base" AND $filter->pr == 1 ? "mactive" : ""?>" href="<?=app\helpers\OrganizationUrl::to(array_merge(["/tests/base/index"],$get))?>"><?=Yii::t("main","Мои тесты")?></a></li>
                <?php
                $get['filter']['pr'] = 2;
                ?>
                <li class="nav-item"><a class="nav-link <?=$this->context->id == "base" AND $filter->pr == 2 ? "mactive" : ""?>" href="<?=app\helpers\OrganizationUrl::to(array_merge(["/tests/base/index"],$get))?>"><?=Yii::t("main","Все тесты")?></a></li>
                <?php if (!Yii::$app->request->get("assign")) { ?>
                    <li class="nav-item"><a class="nav-link <?=$this->context->id == "questions" ? "mactive" : ""?>" href="<?=app\helpers\OrganizationUrl::to(["/tests/questions/index"])?>"><?=Yii::t("main","Банк вопросов")?></a></li>
                <?php } ?>
            </ul>
        </ul>

        <div class="pull-left" style="margin-left:45px;">
            <a target="_full" href="<?=app\helpers\OrganizationUrl::to(["/tests/constructor/add"])?>" class="btn btn-warning"><?=Yii::t("main","Добавить вопросы")?></a>
        </div>

        <div class="clearfix"></div>
    </div>

    <?=$this->render("_list", [
        'filter' => $filter,
        'themes' => $themes,
        'provider' => $provider
    ])?>

</div>