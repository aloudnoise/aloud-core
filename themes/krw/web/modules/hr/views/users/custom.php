<?php if ($model->role == 'pupil' OR Yii::$app->request->get("type") == \app\models\filters\UsersFilter::TYPE_PUPILS) { ?>
<div class="mt-2 mb-3 page-header"><h6><?=Yii::t("main","Информация о слушателе")?></h6></div>

<div class="row">
    <?php
        $fields = \app\models\DicValues::findByDic("pupil_custom_fields");
        if ($fields) {
            foreach ($fields as $field) {
                ?>
                <div class="col-4 mb-2">
                    <div class="form-group autocomplete" name="<?= $field->value ?>">
                        <label class="control-label"><?= $field->nameByLang ?></label>
                        <input placeholder="<?= $field->nameByLang ?>" name="custom[<?= $field->value ?>]" class="form-control"
                               value="<?= $model->custom[$field->value] ?>"/>
                    </div>
                </div>
                <?php
            }
        }
    ?>
</div>
<?php } ?>