<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." media" : "media";
?>

<div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >

    <div class="media-left">
        <a <?=!$this->context->readonly ? "href='".app\helpers\OrganizationUrl::to(["/events/view", "id" => $this->context->model->id])."'" : ""?>>
            <img style="max-width:24px;" src='<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/menu/events.png"?>' />
        </a>
    </div>

    <div class="media-body">
        <p class="media-heading" style=" margin-bottom:5px;">
            <?php if (!$this->context->readonly) { ?>
                <a href="<?=app\helpers\OrganizationUrl::to(["/events/view", "id" => $this->context->model->id])?>"><?php echo $this->context->model->name; ?></a>
            <?php } else { ?>
                <span><?php echo $this->context->model->name; ?></span>
            <?php } ?>
        </p>
        <? if ($this->context->model->shortDescription) { ?><p class="text-muted" style="margin: 5px 0;"><?php echo $this->context->model->shortDescription; ?></p><? } ?>
        <p class="text-muted shade date mt-2">
            <span class="inline-block">
                <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                    "time" => $this->context->model->begin_ts,
                    "formatType" => 2
                ]) ?>
            </span>
            <span class="inline-block">-</span>
            <span class="inline-block">
                <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                    "time" => $this->context->model->end_ts,
                    "formatType" => 2
                ]) ?>
            </span>
        </p>
    </div>

</div>