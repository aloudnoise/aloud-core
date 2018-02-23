<?php
$this->setTitle($task->name);
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
?>
<div class="action-content">

    <script type="text/template" id="time_left_template">
        <div class="time-left text-center text-white p-2">
            <%
            var time = data.model.get("timeLeft");
            %>
            <div class="inline-block">
                <p><?=Yii::t("main","Время")?>:</p>
            </div>
            <div class="inline-block">
                <p class="<%=time/10 > 10 ? "text-primary" : "text-danger"%>"><strong> <%=Math.floor(time/60) < 10 ? "0" + Math.floor(time/60) : Math.floor(time/60)%> : <%=(time % 60) < 10 ? "0" + (time % 60) : (time % 60)%></strong></p>
            </div>
        </div>
    </script>

    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions" => [
            "action" => \app\helpers\OrganizationUrl::to(array_merge(["/tasks/process"], \Yii::$app->request->get())),
            "method" => "post",
            "id" => "TaskResultsForm"
        ],
    ]);
    echo \app\widgets\EUploader\EUploader::widget([
        "standalone" => true
    ]);
    (Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();
    ?>

    <div class="white-block">
        <div class="row">
            <div class="col-auto align-self-center">
                <h5 class="pull-left text-info">
                    <?=$task->name?>
                </h5>
            </div>
            <div class="col-auto ml-auto">
                <div class="time-left">

                </div>
            </div>
        </div>
    </div>

    <div class="white-block mt-1 ">
        <div class="row ">
            <div class="col py-3">
                <?=$task->content?>
            </div>
        </div>

        <?php if ($task->files) { ?>

            <div class="mt-3 mb-3">
                <h5 class="mb-3"><?=Yii::t("main","Прикрепленные документы")?></h5>
                <?php foreach ($task->files as $file) { ?>
                    <div class="alert alert-secondary mb-1">
                        <a target="_blank" href="<?=$file['url']?>"><?=$file['name']?></a>
                    </div>
                <?php } ?>
            </div>

        <?php } ?>

    </div>

    <div class="white-block mt-1">
        <div class="form-group mb-2" attribute="answer">
            <label class="control-label"><?=$model->getAttributeLabel("answer")?></label>
            <?=\app\helpers\Html::textarea("answer", $model->answer, [
                'id' => 'result_textarea',
                'textareatype' => 'ckeditor',
                'cktype' => 'full',
                "class" => "form-control task-result-textarea",
                "placeholder" => Yii::t("main", "Поле для ввода ответа"),
                "rows" => 12
            ])?>
        </div>


        <div class="form-group mb-0">
            <input type="submit" value="<?=Yii::t("main","Сдать на проверку")?>" class="btn btn-primary btn-lg" />
        </div>

    </div>



    <?php \app\widgets\EForm\EForm::end(); ?>
</div>