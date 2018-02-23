<div class="white-block">
    <div class="row mb-3">
        <div class="col">
            <h5>
                <?=Yii::t("main","Темы")?>
            </h5>
        </div>
        <?php if ($model->canEdit AND !$readonly) { ?>
            <div class="col col-auto ml-auto">
                <a href="<?=\app\helpers\OrganizationUrl::to(['/tests/questions/index', 'from' => $from->params, 'return' => Yii::$app->request->url])?>" class="text-success"><i class="fa fa-list"></i> <?=Yii::t("main","Прикрепить тему")?></a>
            </div>
        <?php } ?>
    </div>
    <hr/>
    <?php if (!empty($model->themes)) { ?>
        <?php foreach ($model->themes as $theme) { ?>
            <div class="theme mt-2 pb-2 relative bordered-bottom">
                <div class="row">
                    <div class="col">
                        <h6><?=$theme->theme?></h6>
                    </div>
                    <?php if ($model->canEdit AND !$readonly) { ?>
                        <div class="col col-auto ml-auto">
                            <div class="text-right">
                                <a href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type" => 8, "tid" => $theme->id, "eid" => $model->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить тему?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
    <?php } ?>
</div>