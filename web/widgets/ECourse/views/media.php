<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." media" : "media";
?>

<div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >

    <div class="media-left">
        <a <?=!$this->context->readonly ? "href='".app\helpers\OrganizationUrl::to(["/courses/view", "id" => $this->context->model->id])."'" : ""?>>
            <span class="icon-circle img-icon icon-circle-lg bg-info"><i class="icon-1"></i></span>
        </a>
    </div>

    <div class="media-body">
        <p class="media-heading" style=" margin-bottom:5px;">
            <?php if (!$this->context->readonly) { ?>
                <a href="<?=app\helpers\OrganizationUrl::to(["/courses/view", "id" => $this->context->model->id])?>"><?php echo $this->context->model->name; ?></a>
            <?php } else { ?>
                <span><?php echo $this->context->model->name; ?></span>
            <?php } ?>
        </p>
        <? if ($this->context->model->shortDescription) { ?><p class="text-muted" style="margin: 5px 0;"><?php echo $this->context->model->shortDescription; ?></p><? } ?>
        <p class="text-muted shade date mt-2">
            <span class='inline-block' style="margin-right:10px;"><?=Yii::t("main","Уроков: <b class='text-danger'>{l_count}</b>", [
                    "l_count" => $this->context->model->lcount
                ])?></span>
            <span class="inline-block">
                <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                    "time" => $this->context->model->ts,
                    "formatType" => 2
                ]) ?>
            </span>
            <span class="inline-block" style="margin-left:15px;">
                <i class="fa fa-eye"></i> <?=$this->context->model->viewsCount?>
            </span>
        </p>
    </div>

</div>