<div class="mt-3 white-block filter-panel">
    <div class="row">
        <?php if (!Yii::$app->request->get("assign") AND \common\models\Organizations::getCurrentOrganizationId() !== 0) { ?>
            <div class="col col-auto">
                <a target="modal"  href="<?=\app\helpers\OrganizationUrl::to(["/hr/users/add", 'type' => \app\models\filters\UsersFilter::TYPE_STAFF])?>" class="btn btn-success">
                    <?=Yii::t("main","Добавить пользователя")?></a>
            </div>
        <?php } ?>
        <div class="col-12 mt-3">
            <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень сотрудников системы")?></p>
            <p class="text-muted mt-2"><?=Yii::t("main","Для создания нового пользователя, нажмите кнопку {add_link}, и заполните необходимые поля: фио, логин, email, номер телефона и другие.", [
                    'add_link' => "<a target=\"modal\" class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/hr/users/add", "type" => \app\models\filters\UsersFilter::TYPE_STAFF])."'>".Yii::t("main","Добавить пользователя")."</a>"
                ])?></p>
        </div>
    </div>

    <hr />

    <div class="">
        <div class="row">
            <div class="col">
                <input data-role="filter" data-action="input" placeholder="<?= Yii::t("main", "Поиск") ?>" type="text" value="<?= $filter->search ?>" class="form-control" name="search"/>
            </div>
            <div class="col col-auto">
                <a href="#" data-role="filter" data-action="submit" class="btn btn-outline-primary"><?=Yii::t("main","Найти")?></a>
            </div>
        </div>
    </div>

    <hr />

    <div class="">
        <table class="users table table-sm table-bordered table-hover mb-0">

            <thead>
            <tr>
                <th><?=Yii::t("main","ФИО")?></th>
                <th><?=Yii::t("main","Логин")?></th>
                <th><?=Yii::t("main","Email/Телефон")?></th>
                <th><?=Yii::t("main","Роль")?></th>
                <th><?=Yii::t("main","Дата создания")?></th>
                <th><?=Yii::t("main","Статус")?></th>
            </tr>
            </thead>

            <tbody style="border-top:0;" class="users-body ">
            <?php $users = $provider->getModels() ?>
            <?php if ($users) { ?>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td style="vertical-align: middle;"><?=$user->user->fio?></td>
                        <td style="vertical-align: middle;"><?=$user->user->login?></td>
                        <td style="vertical-align: middle;">
                            <p><?=$user->user->email?></p>
                            <p><?=$user->user->phone?></p>
                        </td>
                        <td style="vertical-align: middle;"><?=\common\models\Users::getRoles()[$user->role]?></td>
                        <td style="vertical-align: middle;"><?=$user->getByFormat('ts', 'd.m.Y')?></td>
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
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>

        </table>

        <?= \app\widgets\EPager\EPager::widget([
            'pagination' => $provider->pagination,
        ]) ?>
    </div>
</div>