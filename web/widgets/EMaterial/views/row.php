<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." assign-item" : "assign-item";
?>

<tr assign_item="material" assign_id="<?=$this->context->model->id?>" <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >

    <?php if ($this->context->number) { ?>
        <td style="vertical-align: middle;">
            <?=$this->context->number?>
        </td>
    <?php } ?>

    <td style="border-right:0; vertical-align: middle;">
        <img style="max-width:48px;" src='<?=Yii::$app->assetManager->getBundle("base")->baseUrl."/img/icons/".$this->context->model->icon?>.png' />
    </td>
    <td style="border-left:0; vertical-align: middle;" >
        <a <?=isset($this->context->link['target']) ? "target='".$this->context->link['target']."'" : ""?>  href="<?=$this->context->link['href'] ? $this->context->link['href'] : app\helpers\OrganizationUrl::to(["/library/view", "id"=>$this->context->model->id])?>"><?php echo $this->context->model->shortName; ?></a>
        <p>
            <?=$this->context->model->materialInfoString?>
        </p>
    </td>
    <td style="vertical-align: middle;">
        <?php echo $this->context->model->theme; ?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo $this->context->model->tagsString; ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \app\widgets\EDisplayDate\EDisplayDate::widget([
            "time" => $this->context->model->ts,
        ]) ?>
    </td>
    <td style="vertical-align: middle;" class="text-nowrap">
        <i class="fa fa-eye"></i> <?=$this->context->model->viewsCount?>
        <? if ($this->context->model->type == \app\models\Materials::TYPE_FILE) { ?>
            <i class="fa fa-cloud-download ml-1"></i> <?=$this->context->model->downloadsCount?>
        <? } ?>
        <i title="<?=$this->context->model->is_shared ? Yii::t("main","Общедоступен") : Yii::t("main","Личный")?>" class="fa fa-users ml-1 text-<?=$this->context->model->is_shared ? "success" : "danger"?>"></i>
    </td>
</tr>