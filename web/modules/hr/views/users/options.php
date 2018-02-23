<?php
$this->setTitle(Yii::t("main","Настройки теста"));
?>

<div class="action-content">
    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions"=>[
            "action"=>app\helpers\OrganizationUrl::to(array_merge(["/hr/users/options"], \Yii::$app->request->get())),
            "method"=>"post",
            "id"=>"userOptionsForm"
        ],
    ]);
    ?>

    <div class="white-block">

        <div class="form-group" attribute="criteria">
            <label class="control-label"><?=Yii::t("main","Критерии оценивания")?></label>
            <div class="input-group">
                <input type="text" name="criteria" class="form-control" value="<?=$model->criteria?>" />
                <div class="input-group-btn">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=Yii::t("main","Из шаблона")?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php if (!empty($criteria_templates)) { ?>
                            <?php foreach ($criteria_templates as $template) { ?>
                                <a target="modal" class="dropdown-item" href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/options', 'id'=>$user->id, 'template_id' => $template->id])?>"><?=$template->name."(".$template->infoJson['template'].")"?></a>
                            <?php } ?>
                        <?php } else { ?>
                            <span style="display:block;" class="m-4 text-danger"><strong><?=Yii::t("main","Шаблонов не найдено")?></strong></span>
                        <?php } ?>
                        <div role="separator" class="dropdown-divider"></div>
                        <a target="modal" class="dropdown-item" href="<?=\app\helpers\OrganizationUrl::to(['/dics/add', 'dic' => 'test_criteria_templates', 'return' => \Yii::$app->request->url])?>"><?=Yii::t("main","Добавить шаблон")?></a>
                    </div>
                </div>
            </div>
            <p class="help-bloc mt-1 text-very-light-gray"><?=Yii::t("main","Пример: 0-40:2,41-60:3,61-80:4,81-100:5")?></p>
        </div>

        <div class="form-group text-center" style="margin-top:45px;">
            <input type="submit" value="<?=Yii::t("main","Сохранить")?>" class="btn btn-primary btn-lg" />
        </div>
    </div>
    <?php \app\widgets\EForm\EForm::end(); ?>
</div>