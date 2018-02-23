<div class="row">
    <div class="col col-auto">
        <h3 class="text-primary"><?= $n ?></h3>
    </div>
    <div class="col">
        <h4><?= $test_theme->theme->nameByLang ?></h4>
    </div>
</div>
<div class="row mt-2">
    <div class="col col-auto">
        <?=Yii::t("main","Вопросов: ")?> <strong class="text-muted"><?=count($test_theme->theme->questions)?></strong>
    </div>
    <div class="col col-auto">
        <?=Yii::t("main","Ставка: ")?>
        <span class="text-muted">
            <?=Yii::t("main","Не более")?>
            <strong class=""><?=$test_theme->weight?></strong>
            <?php if ($test_theme->weight_type == \app\models\relations\TestTheme::WEIGHT_TYPE_PERCENT) { ?>
                <?=\common\helpers\Common::multiplier($test_theme->weight, [
                    Yii::t("main","процента"),
                    Yii::t("main","процентов"),
                    Yii::t("main","процентов")
                ])?>
            <?php } else { ?>
                <?=\common\helpers\Common::multiplier($test_theme->weight, [
                    Yii::t("main","вопроса"),
                    Yii::t("main","вопросов"),
                    Yii::t("main","вопросов")
                ])?>
            <?php } ?>
        </span>
    </div>
</div>