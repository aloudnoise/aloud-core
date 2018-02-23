<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." media" : "media";

$link = app\helpers\OrganizationUrl::to(array_merge(["/tests/process/begin"], [
    "id" => $this->context->model->id,
    "from" => $this->context->from->params,
    "return"=>Yii::$app->request->url,
]));

$result = \app\models\results\TestResults::getMyResult($this->context->model->id, $this->context->from);

?>

<div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >
    <div class="row">
        <div class="col-auto align-self-center">
            <span class="icon-circle img-icon icon-circle-lg bg-info"><i class="icon-12"></i></span>
        </div>
        <div class="col align-self-center">
            <p class="mb-0">
                <?php if ($this->context->readonly) { ?>
                    <?php echo $this->context->model->name; ?>
                <?php } else { ?>
                    <a href="<?=$link?>"><?php echo $this->context->model->name; ?></a>
                <?php } ?>
            </p>
            <div class="row">

                <div class="col-auto">
                    <p class="text-muted"><?=Yii::t("main","Вопросов: <b class='text-danger'>{q_count}</b>", [
                            "q_count" => $this->context->model->qcount
                        ])?></p>
                </div>

                <div class="col-auto">
                    <p class="text-muted"><?=Yii::t("main","Время: <b class='text-danger'>{time}м.</b>", [
                            "time" => $this->context->model->time
                        ])?></p>
                </div>

                <?php if ($result !== null) { ?>
                    <div class="col-auto ml-auto">
                        <span><?=Yii::t("main","Результат")?> </span><b style="border-radius:100%;" class='p-3 text-<?=$result->resultTextColor?>'><?=$result->translatedResultText?></b>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>