<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." assign-item" : "assign-item";
?>

<tr assign_item="task" assign_id="<?=$this->context->model->id?>" <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >
    <td style="border-right:0; vertical-align: middle;">
        <a <?=isset($this->context->link['target']) ? "target='".$this->context->link['target']."'" : ""?>  href="<?=$this->context->link['href'] ? $this->context->link['href'] : app\helpers\OrganizationUrl::to(["/tasks/view", "id"=>$this->context->model->id])?>" class="icon-circle img-icon icon-circle-lg bg-warning"><i class="icon-5"></i></a>
    </td>
    <td style="border-left:0; vertical-align: middle;" >
        <a <?=isset($this->context->link['target']) ? "target='".$this->context->link['target']."'" : ""?>  href="<?=$this->context->link['href'] ? $this->context->link['href'] : app\helpers\OrganizationUrl::to(["/tasks/view", "id"=>$this->context->model->id])?>"><?php echo $this->context->model->shortName; ?></a>
    </td>
    <td style="vertical-align: middle;">
        <?php echo $this->context->model->shortContent; ?>
    </td>
    <td style="vertical-align: middle;">
        <?=$this->context->model->time?>м.

        <i title="<?=$this->context->model->is_shared ? Yii::t("main","Общедоступен") : Yii::t("main","Личный")?>" class="fa fa-users ml-1 text-<?=$this->context->model->is_shared ? "success" : "danger"?>"></i>
    </td>
</tr>