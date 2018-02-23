<?php
\Yii::$app->breadCrumbs->addLink(\Yii::t("main", "Голосования"), \app\helpers\OrganizationUrl::to(["/polls/index"]));
if (\Yii::$app->request->get('id')) {
    $this->addTitle((Yii::t("main", "Редактирование голосования")));
} else {
    $this->addTitle((Yii::t("main", "Добавление голосования")));
}
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
?>
<div class="action-content">
    <div class="row">
        <div class="col">
            <div class="white-block border-warning">
                <?php
                $f = \app\widgets\EForm\EForm::begin([
                    "htmlOptions" => [
                        "action" => \app\helpers\OrganizationUrl::to(array_merge(["/polls/add"], \Yii::$app->request->get())),
                        "method" => "post",
                        "id" => "newPollForm"
                    ],
                ]);
                ?>

                <div class="form-group mb-3" attribute="name">
                    <label for="name" class="control-label"><?=$model->getAttributeLabel("name")?></label>
                    <input class="form-control" type="text" placeholder="<?=$model->getAttributeLabel("name")?>" id="name" value="<?=$model->name?>" name="name" />
                </div>

                <hr style="margin-left:-15px; margin-right:-15px;" />

                <div class="form-group" attribute="question">
                    <div class="row">
                        <div class="col col-auto align-self-center">
                            <h5 class="control-label mb-1"><?=Yii::t("main","Вопрос")?></h5>
                        </div>
                        <div class="col">
                            <textarea class="form-control" name="question" rows="1" placeholder="<?=Yii::t("main","Введите свой вопрос")?>"><?=$model->question?></textarea>
                        </div>
                    </div>
                </div>

                <hr style="margin-left:-15px; margin-right:-15px;" />

                <div class="answers form-group" attribute="answers">

                    <h5 class="mb-4"><?=Yii::t("main","Варианты ответа")?></h5>

                    <?php
                    $answers = $model->answers ?: [
                        ['id' => '', 'name' => ''],
                        ['id' => '', 'name' => ''],
                        ['id' => '', 'name' => '']
                    ]
                    ?>

                    <?php $i = 1; foreach ($answers as $answer) { ?>
                        <div class="answer mb-3" i="<?=$i?>">
                            <input type="hidden" name="answers[<?=$i?>][id]" value="<?=$answer['id']?>" />
                            <div class="row">
                                <div class="col align-self-center">
                                    <textarea class="form-control answer-name mb-0" name="answers[<?=$i?>][name]" rows="1" placeholder="<?=Yii::t("main","Введите вариант ответа")?>"><?=$answer['name']?></textarea>
                                </div>
                                <div class="col-auto text-nowrap align-self-center">
                                    <a class="pointer add-variant text-muted " title="<?=Yii::t("main","Добавить вариант ответа")?>" style="font-size:1.2rem;"><strong><i class="fa fa-plus"></i></strong></a>
                                    <a class="ml-2 pointer delete-variant text-muted " title="<?=Yii::t("main","Удалить вариант ответа")?>" style="font-size:1.2rem;"><strong><i class="fa fa-minus"></i></strong></a>
                                </div>
                            </div>
                        </div>
                        <?php $i++; } ?>

                </div>

                <div class="form-group mb-3" attribute="tagsString">
                    <label class="control-label"><?=Yii::t("main","Ключевые слова")?></label>
                    <input value="<?=$model->tagsString?>" class="form-control" name="tagsString" placeholder="<?=Yii::t("main","Начните набирать слово...")?>" />
                    <p class="help-block text-very-light-gray mt-1"><?=Yii::t("main","В данном поле необходимо написать как можно больше ключевых слов, относящихся к добавляемой новости. Это облегчит поиск новостей")?></p>
                </div>

                <div class="form-group mb-3" attribute="status">
                    <div class="custom-control custom-checkbox">
                        <input id="activated_input" <?=$model->status == \app\models\Polls::STATUS_ACTIVE ? "checked" : ""?> type="checkbox" class="custom-control-input" name="status" value="1">
                        <label for="activated_input" class="custom-control-label"><?=Yii::t("main","Активирован")?></label>
                    </div>
                </div>

                <div class="form-group mt-5 mb-0">
                    <input type="submit" class="btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />
                    <?php if ($model->isNewRecord) { ?>
                        <a class="btn btn-outline-danger" href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/polls/index'], Yii::$app->request->get()))?>"><?=Yii::t("main","Отмена")?></a>
                    <?php } ?>
                </div>
                <?php \app\widgets\EForm\EForm::end(); ?>
            </div>
        </div>
    </div>
</div>









