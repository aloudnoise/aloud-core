<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." media" : "media";

$link = app\helpers\OrganizationUrl::to(array_merge(["/tasks/begin"], [
    "id" => $this->context->model->id,
    "return"=>Yii::$app->request->url,
    "from" => $this->context->from->params
]));

$result = \app\models\results\TaskResults::getMyResult($this->context->model->id, $this->context->from);

?>

<div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >

    <div class="row">
        <div class="col-auto align-self-center">
            <span class="icon-circle img-icon icon-circle-lg bg-info"><i class="icon-5"></i></span>
        </div>
        <div class="col-auto align-self-center">
            <p class="mb-2">
                <?php if ($this->context->readonly) { ?>
                    <?php echo $this->context->model->name; ?>
                <?php } else { ?>
                    <a href="<?=$link?>"><?php echo $this->context->model->name; ?></a>
                <?php } ?>
            </p>
            <div class="row">
                <?php if ($result !== null) { ?>
                    <?php if ($result->result !== null) { ?>
                        <div class="col-auto">
                            <span class="mr-3"><?=Yii::t("main","Оценка учителя:")?></span> <span class="text-<?=$result->resultTextColor?>"><?=$result->translatedResultText?></span>
                        </div>
                    <?php } else { ?>
                        <div class="col-auto">
                            <span class="text-info"><?=Yii::t("main","Ожидает проверки")?></span>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

    </div>
</div>