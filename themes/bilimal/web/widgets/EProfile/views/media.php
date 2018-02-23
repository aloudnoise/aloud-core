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
            <?php if ($this->context->model->getCurrentOrganizationRole() == "pupil") { ?>

                <?php $custom_fields = \app\models\DicValues::findByDic("pupil_custom_fields"); ?>
                <?php if ($custom_fields) { ?>
                    <small>
                        <div class="row mt-1">
                            <?php foreach ($custom_fields as $field) { ?>
                                <?php if (in_array($field->value, ['group_name','group_course']) AND isset($this->context->model->currentOrganizationRelation->{$field->value})) { ?>
                                    <div class="col-auto">
                                        <span class="text-muted"><?=$field->name?>:</span> <?=$this->context->model->currentOrganizationRelation->{$field->value}?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </small>
                <?php } ?>

            <?php } else { ?>
                <p class="mt-1 text-very-light-gray"><?=\app\models\Users::getRoles()[$this->context->model->getCurrentOrganizationRole()]?></p>
            <?php } ?>
        </div>
    </div>

</div>