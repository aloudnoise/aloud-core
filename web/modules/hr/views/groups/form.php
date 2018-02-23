<?php
$this->addTitle( (\Yii::$app->request->get('id') )?(Yii::t("main","Редактирование группы")):(Yii::t("main","Добавление группы")));
?>

<div class="action-content">

    <div class="white-block border-warning">
        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions"=>[
                "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/hr/groups/add"], \Yii::$app->request->get(null, []))),
                "method"=>"post",
                "id"=>"newGroupForm"
            ],
        ]);
        ?>

        <div class="form-group mb-3" attribute="name">

            <label for="name" class="control-label"><?=$model->getAttributeLabel("name")?></label>
            <input class="form-control" type="text" placeholder="<?=$model->getAttributeLabel("name")?>" id="name" value="<?=$model->name?>" name="name" />

        </div>

        <div class="form-group mb-3"  attribute="description">
            <label for="description" class="control-label"><?=$model->getAttributeLabel("description")?></label>
            <textarea name="description" class="form-control" placeholder="<?=Yii::t("main","Введите описание группы")?>"><?=$model->description?></textarea>
        </div>

        <div class="mt-3 submit-panel">

            <input type="submit" class="pointer btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />

        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>

</div>









