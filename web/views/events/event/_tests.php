<?php if (!empty($model->tests)  OR $model->canEdit) { ?>
    <div class="white-block mb-4">
        <div class="row">
            <div class="col">
                <h5>
                    <?=Yii::t("main","Тесты")?>
                </h5>
            </div>
            <?php if ($model->canEdit AND !$readonly) { ?>
                <div class="col col-auto ml-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/tests/base/index', 'from' => $from->params, 'return' => Yii::$app->request->url])?>" class="text-success"><i class="fa fa-list"></i> <?=Yii::t("main","Прикрепить тест")?></a>
                </div>
            <?php } ?>
        </div>
        <hr />
        <?php
        if (!empty($model->tests)) { ?>
            <?php foreach ($model->tests as $test) { ?>
                <div class="test mt-2 pb-2 relative bordered-bottom">
                    <div class="row">
                        <div class="col">
                            <?php
                            echo \app\widgets\ETest\ETest::widget([
                                "model" => $test->test,
                                "readonly" => !$readonly ? ($model->isParticipant ? false : true) : true,
                                "backbone" => false,
                                "from" => $process
                            ]);
                            ?>
                        </div>
                        <?php if ($model->canEdit AND !$readonly) { ?>
                            <div class="col col-auto ml-auto">
                                <div class="text-right">
                                    <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/tests/base/options", 'id'=>$test->related_id, 'from' => $from->params])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" title="<?=Yii::t("main","Настройки")?>" class="text-primary"><i class="fa fa-gears"></i></a>
                                    <a href="<?=\app\helpers\OrganizationUrl::to(["/events/delete", "type" => 4, "tid" => $test->id, "eid" => $model->id])?>" style="cursor:pointer; font-size:16px; margin-left:5px;" confirm='<?=Yii::t("main","Вы уверены, что хотите открепить задание?")?>' title="<?=Yii::t("main","Открепить")?>" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row mt-1">
                        <div class="col-auto ml-auto">
                            <?php if ($test->criteria) { ?>
                                <small>
                                    <?=Yii::t("main","Критерий:")?> <span class="text-muted"><?=$test->criteria?></span>
                                    <?php if ($model->canEdit) { ?>
                                        <a title="<?=Yii::t("main","Обновить результаты тестирования по критериям")?>" href="<?=\app\helpers\OrganizationUrl::to(['/tests/process/refresh-criteria', 'id' => $test->test->id, 'from' => (new \common\models\From(['event', $model->id, 'refresh']))->getParams(), 'return' => Yii::$app->request->url])?>"><i class="fa fa-refresh"></i></a>
                                    <?php } ?>
                                </small>
                            <?php } ?>
                            <?php if ($test->password) { ?>
                                <small class="text-success"><?=Yii::t("main","Пароль")?></small>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-warning mb-0"><?=Yii::t("main","Не назначено")?></div>
        <?php } ?>
    </div>
<?php } ?>