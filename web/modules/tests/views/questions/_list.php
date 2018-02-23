<div class="white-block">
    <div class="form-group">
        <div class="row">
            <div class="col-sm-5" style="padding-right:3px;">
                <input placeholder="<?=Yii::t("main","Поиск вопросов по названию")?>" type="text" value="<?=$filter->search?>" class="find-input form-control autocomplete" autocomplete-attribute="name" />
            </div>
            <div class="col-sm-5" style="padding-right:3px;">
                <?php
                $themes =  [""=>Yii::t("main","Тема вопросов")] + \yii\helpers\ArrayHelper::map($themes, "id", "name");
                ?>
                <?=\app\helpers\Html::dropDownList("theme_id", $filter->theme_id, $themes, [
                    "class"=>"form-control themes-select"
                ]);?>
            </div>
            <div class="col-xs-2" style="padding-left:0;">
                <a class="find-button btn btn-outline-dark"><?=Yii::t("main","Найти")?></a>
            </div>
        </div>
    </div>
</div>

<div class="white-block mt-1">

    <?php if ($provider->totalCount > 0) { ?>
        <div class="tests-container">
            <?php $questions = $provider->getModels(); ?>
            <?php foreach ($questions as $question) {
                ?>
                <?=$this->render("@app/modules/tests/views/common/question", [
                    'question' => $question,
                ])?>
                <?php if ($n != count($questions)) { ?>
                    <hr />
                <?php } ?>
            <?php } ?>

            <?= \app\widgets\EPager\EPager::widget([
                'pagination' => $provider->pagination,
            ]) ?>

        </div>

    <?php } else if ($filter->search) { ?>

        <div class="alert alert-danger">
            <?= Yii::t("main", "Вопросов не найдено");?>
        </div>

    <?php } else { ?>

        <div class="alert alert-danger">
            <?= Yii::t("main", "Еще не добавлено ниодного вопроса");?>
        </div>

    <?php } ?>

</div>