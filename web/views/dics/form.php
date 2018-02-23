<?php

(Yii::$app->assetManager->getBundle("tools"))::registerChosen($this);
\Yii::$app->breadCrumbs->addLink(\Yii::t('main', 'Список словарей'), \app\helpers\OrganizationUrl::to(['/dics/index']));

$title = Yii::t('main', 'Добавление значения');
if (!$dicv->isNewRecord) {
    $title = Yii::t('main', 'Редактирование значения');
}
$this->addTitle($title);
?>

<div class="action-content">

    <?php
    $f = \app\widgets\EForm\EForm::begin([
        'htmlOptions' => [
            'action' => \app\helpers\OrganizationUrl::to(array_merge(['/dics/add'], \Yii::$app->request->get(null, []))),
            'method' => 'post',
            'id' => 'newDicsvForm'
        ],
    ]);
    ?>

    <div class="form-group" attribute="name">
        <label for="name" class="control-label"><?= Yii::t('main', 'Название') ?></label>
        <input class="form-control" type="text" placeholder="<?= Yii::t('main', 'Название') ?>"
               id="name" value="<?= $dicv->name ?>" name="name"/>
    </div>

    <?php
    $fields = $dicv->formFields();
    if (!empty($fields)) {?>

        <?php foreach ($fields as $name=>$field) { ?>
            <div class="form-group" attribute="<?=$name?>">
                <label for="name" class="control-label"><?= $field['label'] ?></label>
                <input class="form-control" type="<?=$field['type']?>"
                       placeholder="<?= $field['label'] ?>" id="<?= $name ?>"
                       value="<?= @$dicv->$name ?>"  name="<?= $name ?>" />
                <?php if ($field['help']) { ?>
                    <p class="help-block text-very-light-gray mt-1"><?=$field['help']?></p>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="form-group">
        <input type="submit" class="btn btn-success" value="<?= Yii::t('main', 'Сохранить') ?>"/>
    </div>
    <?php \app\widgets\EForm\EForm::end(); ?>
</div>