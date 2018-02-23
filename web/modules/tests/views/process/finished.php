<?php
(Yii::$app->assetManager->getBundle("tools"))::registerChosen($this);
$this->setTitle(Yii::t("main","Тест завершен"), false);
?>
<div class="action-content">

	<div class="white-block">

        <h3 class=""><?=Yii::t("main","Тест завершен")?></h3>
        <div class="row mt-3">
            <div class="col-auto align-self-center">
                <h4 style="margin-bottom:2px;"><?=Yii::t("main","Результат:")?></h4>
            </div>
            <div class="col-auto">
                <h1 class="ml-3 text-<?=$test->resultTextColor?>"><?=$test->translatedResultText?></h1>
            </div>
        </div>

        <?php

        if ($themes) {

            foreach ($themes as $theme) {

                ?>

                <h4 class="mt-3"><?=Yii::t("main","{theme}: {ball}%", [
                        "theme" => "<span>".$theme->name."</span>",
                        "ball" => "<span class='".\app\models\results\TestResults::textColor($test->infoJson['by_themes'][$theme->id])."'>".$test->infoJson['by_themes'][$theme->id]."</span>"
                    ])?></h4>

                <?php

            }

        }

        ?>

        <div class="mt-3">
            <?php if ($test->test->is_repeatable) { ?>
                <a href="<?app\helpers\OrganizationUrl::to(["/tests/process/begin", 'id' => $test->test->id])?>" class="btn btn-primary btn-lg" ><?=Yii::t("main","Пройти повторно")?></a>
            <?php } ?>
            <a href="<?=Yii::$app->request->get("return") ? Yii::$app->request->get("return") : app\helpers\OrganizationUrl::to(["/main/index"])?>" class="btn btn-primary btn-lg" ><?=Yii::t("main","Вернуться")?></a>
        </div>
	</div>

</div>