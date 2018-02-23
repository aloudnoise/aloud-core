<?php $lists = $lists ?: $filter->lists; ?>
<?if ($teacher OR $theme OR $group OR $result) { ?>
<div class="d-print-none">
    <div class="row">

        <?php if ($teacher) { ?>
            <div class="col-3">
                <div class="form-group mb-0">
                    <?=\app\helpers\Html::dropDownList("custom[teacher_id]", $filter->custom['teacher_id'], \app\helpers\ArrayHelper::map($lists['teachers'], 'related_id', 'user.fio'), [
                        'prompt' => Yii::t("main",'По преподавателю'),
                        'class' => 'form-control',
                        'data-role' => 'filter',
                        'data-action' => 'input'
                    ])?>
                </div>
            </div>
        <?php } ?>

        <?php if ($theme) { ?>
            <div class="col-3">
                <div class="form-group mb-0">
                    <?=\app\helpers\Html::dropDownList("custom[theme_id]", $filter->custom['theme_id'], \app\helpers\ArrayHelper::map($lists['themes'], 'id', 'nameByLang'), [
                        'prompt' => Yii::t("main",'По теме обучения'),
                        'class' => 'form-control',
                        'data-role' => 'filter',
                        'data-action' => 'input'
                    ])?>
                </div>
            </div>
        <?php } ?>

        <?php if ($group) { ?>
            <div class="col-3">
                <div class="form-group mb-0">
                    <?=\app\helpers\Html::dropDownList("custom[group_id]", $filter->custom['group_id'], \app\helpers\ArrayHelper::map($lists['groups'], 'id', 'nameByLang'), [
                        'prompt' => Yii::t("main",'По группе'),
                        'class' => 'form-control',
                        'data-role' => 'filter',
                        'data-action' => 'input'
                    ])?>
                </div>
            </div>
        <?php } ?>

        <?php if ($result) { ?>
            <div class="col-3">
                <div class="form-group mb-0">
                    <input class="form-control" type="text" data-role="filter" data-action="input" name="custom[result]" value="<?=$filter->custom['result']?>" placeholder="<?=Yii::t("main","По результату")?>" />
                </div>
            </div>
        <?php } ?>

    </div>
</div>

<hr />

<?php } ?>