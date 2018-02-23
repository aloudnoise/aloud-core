<?php (Yii::$app->assetManager->getBundle("tools"))::registerChosen($this); ?>
<?php $filter = \Yii::$app->request->get("filter", [])?>

<div class="questions-list">

    <div class="form-group mb-3">
        <div class="row">
            <div class="col" style="padding-right:3px;">
                <input placeholder="<?=Yii::t("main","Поиск вопросов по названию")?>" type="text" value="<?=$filter['search']?>" class="find-input form-control autocomplete" autocomplete-attribute="name" />
            </div>
            <div class="col" style="padding-right:3px;">
                <?=\app\helpers\Html::dropDownList("theme_id", $filter['theme_id'], \app\helpers\ArrayHelper::map((new \app\modules\tests\models\constructor\FullTheme())->getThemes(),'id','nameWithQCount'), [
                    'prompt' => Yii::t("main","Поиск по теме"),
                    'class' => 'form-control chosen-select themes-select'
                ])?>
            </div>
            <div class="col col-auto" style="padding-left:0;">
                <a class="find-button btn btn-outline-dark"><?=Yii::t("main","Найти")?></a>
            </div>
        </div>
    </div>
    <?php $provider = $model->getProvider(array_keys(\app\helpers\ArrayHelper::map($questions, 'id', 'id'))); ?>
    <?php if ($provider->totalCount > 0) { ?>
        <div class="tests-container">
            <?php $questions = $provider->getModels(); ?>
            <?php foreach ($questions as $question) {
                ?>
                <?=$this->render("@app/modules/tests/views/common/question", [
                    'question' => $question,
                    'link' => \app\helpers\OrganizationUrl::to(['/tests/constructor/assign', 'id' => $test->id, 'theme_id' => $theme->id, 'q_id' => $question->id, 'return' => Yii::$app->request->url])
                ])?>
                <hr />
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

    <div class="form-group mb-0 mt-3">
        <div class="row">
            <div class="col col-auto ml-auto">
                <a href="<?=\app\helpers\OrganizationUrl::to(['/tests/constructor/compile', 'id' => $test->id, 'theme_id' => $theme->id])?>" class="text-muted btn btn-light"><?=Yii::t("main","Закрыть")?></a>
            </div>
        </div>
    </div>

</div>
