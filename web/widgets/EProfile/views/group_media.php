<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." media" : "media";
?>

<div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >

    <div class="media-left">
        <?php
        $attributes = $this->context->readonly ? [] : [
            "target" => isset($this->context->link['target']) ? $this->context->link['target'] : null,
            "href" => isset($this->context->link['href']) ? $this->context->link['href'] : \app\helpers\OrganizationUrl::to(["/hr/groups/profile", "id"=>$this->context->model->id])
        ];
        ?>
    </div>

    <div class="media-body">
        <p class="media-heading"><<?=$this->context->readonly ? "span" : "a"?> <?=\yii\helpers\Html::renderTagAttributes($attributes)?>><?php echo $this->context->model->name; ?></<?=$this->context->readonly ? "span" : "a"?>></p>
    </div>

</div>