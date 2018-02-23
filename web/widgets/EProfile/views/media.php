<?php
$this->context->htmlOptions['class'] = $this->context->htmlOptions['class'] ? $this->context->htmlOptions['class']." media" : "media";
?>

<div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?> >

    <div class="row justify-content-between">
        <div class="col-auto">
            <?php
                $attributes = $this->context->readonly ? [] : [
                    "target" => isset($this->context->link['target']) ? $this->context->link['target'] : null,
                    "href" => isset($this->context->link['href']) ? $this->context->link['href'] : \app\helpers\OrganizationUrl::to(["/hr/users/profile", "profile_id"=>$this->context->model->id])
                ];
            ?>
            <a <?=\yii\helpers\Html::renderTagAttributes($attributes)?>>
                <img style="max-width:32px;" src='<?=$this->context->model->photoUrl?>' />
            </a>
        </div>
        <div class="col align-self-center">
            <p class="media-heading"><<?=$this->context->readonly ? "span" : "a"?> <?=\yii\helpers\Html::renderTagAttributes($attributes)?>><?php echo $this->context->model->fio; ?></<?=$this->context->readonly ? "span" : "a"?>></p>
            <p class="mt-1 text-very-light-gray"><?=\app\models\Users::getRoles()[$this->context->model->getCurrentOrganizationRole()]?></p>
        </div>
    </div>

</div>