<?php
$filter_fields = \app\helpers\ArrayHelper::map(\app\models\DicValues::findByDic("pupil_custom_fields"), "value", "nameByLang");
if ($this->context->action->id == "index") {
    $fields = array_merge([
        'fio' => Yii::t("main", 'ФИО'),
        'login' => Yii::t("main", 'Логин'),
        'emailPhone' => Yii::t("main", "Email/Телефон"),
        'formattedTs' => Yii::t("main", 'Дата создания')
    ], $filter_fields);
    $checked = $filter->columns ?: ['fio', 'login', 'emailPhone', 'rank', 'division', 'formattedTs'];
} else {
    $fields = array_merge([
        'fio' => Yii::t("main", 'ФИО'),
        'formattedTs' => Yii::t("main", 'Дата создания')
    ], $filter_fields);
    $checked = $filter->columns ?: ['fio', 'rank', 'division', 'formattedTs'];
}

?>

<?php if (!$export) { ?>
    <div class="mt-3 white-block filter-panel">
        <?php if (!Yii::$app->request->get("from") AND \common\models\Organizations::getCurrentOrganizationId() !== 0) { ?>
            <div class="row">
                <div class="col col-auto">
                    <a target="modal"  href="<?=\app\helpers\OrganizationUrl::to(["/hr/users/add", "type" => \app\models\filters\UsersFilter::TYPE_PUPILS])?>" class="btn btn-success">
                        <?=Yii::t("main","Добавить слушателя")?></a>
                </div>

                <div class="col-12 mt-3">
                    <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень слушателей системы")?></p>
                    <p class="text-muted mt-2"><?=Yii::t("main","Для создания нового слушателя, нажмите кнопку {add_link}, и заполните необходимые поля: фио, логин, email, номер телефона и другие.", [
                            'add_link' => "<a target=\"modal\" class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/hr/users/add", 'type' => \app\models\filters\UsersFilter::TYPE_PUPILS])."'>".Yii::t("main","Добавить слушателя")."</a>"
                        ])?></p>
                </div>
            </div>
            <hr/>
        <?php } ?>

        <div class="">
            <?php if (!$filter->show_advanced) { ?>
            <div class="row">
                <div class="col">
                    <input data-role="filter" data-action="input" placeholder="<?= Yii::t("main", "Поиск") ?>" type="text" value="<?= $filter->search ?>" class="form-control" name="search"/>
                </div>
                <div class="col-auto">
                    <a href="#" data-role="filter" data-action="submit" class="btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
                </div>
                <div class="col-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/'.$this->context->action->id, 'filter' => array_merge($filter->attributes, ['show_advanced' => 1])])?>"  class="pointer btn btn-outline-info"><?=Yii::t("main","Расширенный")?></a>
                </div>
            </div>
            <?php } else { ?>
                <div class="advanced-filter">
                    <div class="row">
                        <div class="col-auto mb-2">
                            <input data-role="filter" data-action="input" placeholder="<?= Yii::t("main", "Поиск") ?>" type="text" value="<?= $filter->search ?>" class="form-control" name="search"/>
                        </div>
                        <?php foreach ($filter_fields as $value => $name) { ?>
                            <div class="col-auto mb-2">
                                <input class="form-control" data-role="filter" data-action="input" placeholder="<?=$name?>" type="text" name="custom[<?=$value?>]" value="<?=$filter->custom[$value]?>" />
                            </div>
                        <?php } ?>

                        <div class="col-auto mb-2">
                            <input data-role="filter" data-action="input" placeholder="<?=Yii::t("main","Обучался с")?>" type="text" class="form-control date" name="trained[from]" value="<?=$filter->trained['from']?>" />
                        </div>
                        <div class="col-auto mb-2">
                            <input data-role="filter" data-action="input" placeholder="<?=Yii::t("main","Обучался по")?>" type="text" class="form-control date" name="trained[to]" value="<?=$filter->trained['to']?>" />
                        </div>

                        <div class="col-auto mb-2">
                            <?=\app\helpers\Html::dropDownList('education_view', $filter->education_view, \app\helpers\ArrayHelper::map(\app\models\DicValues::findByDic('education_views'), 'id', 'nameByLang'), [
                                'prompt' => Yii::t("main","Вид обучения"),
                                'class' => 'form-control',
                                'data-role' => 'filter',
                                'data-action' => 'input'
                            ])?>
                        </div>

                        <div class="col-auto mb-2">
                            <?=\app\helpers\Html::dropDownList('education_theme', $filter->education_theme, \app\helpers\ArrayHelper::map(\app\models\DicValues::findByDic('DicQuestionThemes'), 'id', 'nameByLang'), [
                                'prompt' => Yii::t("main","Тема обучения"),
                                'class' => 'form-control',
                                'data-role' => 'filter',
                                'data-action' => 'input'
                            ])?>
                        </div>

                        <div class="col-12"></div>

                        <div class="col-auto ml-auto">
                            <a href="#" data-role="filter" data-action="submit" class="btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
                        </div>
                        <div class="col-auto">
                            <a href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/'.$this->context->action->id, 'filter' => array_merge($filter->attributes, ['show_advanced' => 0])])?>"  class="pointer btn btn-outline-danger"><?=Yii::t("main","Отмена")?></a>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>

    <hr />

    <div class="">

        <?php if ($this->context->action->id == "index") { ?>
            <div class="row">
                <div class="col-auto">
                    <button class="pointer show-columns-filter btn btn-outline-success"><?=Yii::t("main","Колонки")?></button>
                </div>
                <div class="col-auto ml-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(array_merge(['/hr/users/index'], Yii::$app->request->get(), ['export' => 'word']))?>" class="btn btn-outline-warning"><?=Yii::t("main","Экспортировать слушателей")?></a>
                </div>
                <div class="col-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/import'])?>" class="btn btn-outline-info"><?=Yii::t("main","Импортировать слушателей")?></a>
                </div>
            </div>

            <div class="columns-filter mt-3 alert alert-secondary" style="display: none;">
                <div class="row">
                    <?php foreach ($fields as $value => $name) { ?>
                        <div class="col-auto mb-1 mr-3">
                            <div class="custom-control custom-checkbox">
                                <input id="<?=$value?>_input_field" <?=in_array($value, $checked) ? "checked" : ""?> data-role="filter" data-action="input" type="checkbox" class="custom-control-input" name="columns[]" value="<?=$value?>">
                                <label for="<?=$value?>_input_field" class="custom-control-label"><?=$name?></label>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12"></div>
                    <div class="col-auto mt-3">
                        <a href="#" data-role="filter" data-action="submit" class="btn btn-outline-primary"><?=Yii::t("main","Отобразить")?></a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div>
        <?php } ?>
            <table style="width:100%;" class="users table mt-3 table-sm table-bordered table-hover mb-0">

                <?php
                $border_style = '';
                if ($export) {
                    $border_style = 'border-color:#333; border-width:1px; border-style:solid;';
                } ?>

                <tr>
                    <?php foreach ($checked as $column) { ?>
                        <?php if (isset($fields[$column])) { ?>
                            <td style="<?=$border_style?>"><p><?=$fields[$column]?></p></td>
                        <?php } ?>
                    <?php } ?>
                    <?php if (!$export AND $this->context->action->id == "index") { ?>
                        <td><p><?=Yii::t("main","Статус")?></p></td>
                    <?php } ?>
                </tr>

                <?php $users = $provider->getModels() ?>
                <?php if ($users) { ?>
                    <?php foreach ($users as $user) { ?>
                        <tr class="assign-item" assign_item="user" assign_id="<?=$user->user->id?>">
                            <?php foreach ($checked as $column) { ?>
                                <?php if (isset($fields[$column])) { ?>
                                    <?php if ($this->context->action->id == "index") { ?>
                                        <td style="<?=$border_style?> vertical-align: middle;"><p><?=$user->$column?></p></td>
                                    <?php } else { ?>
                                        <td style="<?=$border_style?> vertical-align: middle;"><a href="#"><?=$user->$column?></a></td>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if (!$export AND $this->context->action->id == "index") { ?>
                                <td style="vertical-align: middle;" class="<?=$user->user->is_registered ? "text-success" : "text-warning"?>">
                                    <div class="row">
                                        <div class="col">
                                            <strong>
                                                <?php if ($user->user->is_registered) { ?>
                                                    <i title="<?=Yii::t("main","Зарегистрирован")?>" class="fa fa-check"></i>
                                                <?php } else { ?>
                                                    <i title="<?=Yii::t("main","Незарегистрирован")?>"  class="fa fa-times"></i>
                                                <?php } ?>
                                            </strong>
                                        </div>
                                        <?php if (Yii::$app->user->can($user->role)) { ?>
                                            <div class="col-auto ml-auto text-nowrap">
                                                <a target="modal" class="text-primary" href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/add', 'id' => $user->id])?>"><i class="fa fa-pencil"></i></a>
                                                <a target="modal" class="text-danger" confirm="<?=Yii::t("main", "Вы уверены?")?>" href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/delete', 'id' => $user->id])?>"><i class="fa fa-trash"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
        <?php if (!$export) { ?>
        </div>

        <?= \app\widgets\EPager\EPager::widget([
            'pagination' => $provider->pagination,
        ]) ?>
    </div>
</div>
<?php } ?>