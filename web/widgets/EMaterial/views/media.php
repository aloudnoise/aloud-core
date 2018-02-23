<?php
    $this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." media" : "media";
?>

<div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?>>

    <div class="media-left">
        <a>
            <img style="max-width:32px;" src='<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/icons/".$this->context->model->icon?>.png' />
        </a>
    </div>

    <div class="media-body">
        <p class="media-heading">
        <?php if (!$this->context->readonly) { ?>
            <a <?=isset($this->context->link['target']) ? "target='".$this->context->link['target']."'" : ""?>  href="<?=$this->context->link['href'] ? $this->context->link['href'] : app\helpers\OrganizationUrl::to(["/library/view", "id"=>$this->context->model->id, "from" => $this->context->from->params, "return"=>Yii::$app->request->url])?>"><?php echo $this->context->model->shortName; ?></a>
        <?php }
        else { ?>
            <span><?php echo $this->context->model->shortName; ?></span>
        <?php } ?>
        </p>
        <p class="text-muted"><?php echo $this->context->model->materialInfoString; ?></p>
        <p class="text-muted shade date">
            <small>
                <span>
                    <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time" => $this->context->model->ts,
                    ]) ?>
                </span>
                <span class="ml-2">
                    <i class="fa fa-eye"></i> <?=$this->context->model->viewsCount?>
                    <? if ($this->context->model->type == \common\models\Materials::TYPE_FILE) { ?>
                        <i class="fa fa-cloud-download" style="margin-left:5px;"></i> <?=$this->context->model->downloadsCount?>
                    <? } ?>
                </span>
            </small>
        </p>
    </div>

</div>