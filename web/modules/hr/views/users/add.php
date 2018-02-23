<?php
\Yii::$app->breadCrumbs->addLink(\Yii::t("main","Управление пользователями"), \app\helpers\OrganizationUrl::to(["/hr/users/index"]));
(Yii::$app->assetManager->getBundle("tools"))::registerTool($this, "tagsinput");
$this->addTitle(Yii::t("main","Пользователь"));
?>

<div class="action-content">

    <?php
    $f = \app\widgets\EForm\EForm::begin([
        "htmlOptions"=>[
            "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/hr/users/add"], \Yii::$app->request->get(null, []))),
            "method"=>"post",
            "id"=>"newUserForm"
        ],
    ]);

    ?>

    <?php if (!$model->id) { ?>
        <div class="row mb-4">
            <div class="col">
                <div class="btn-group">
                    <a class="font-weight-6 btn btn-light nav-link <?=!Yii::$app->request->get("existed") ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/hr/users/add"], \Yii::$app->request->get(), ['existed' => null]))?>"><?=Yii::t("main","Новый пользователь")?></a>
                    <a class="font-weight-6 btn btn-light nav-link <?=Yii::$app->request->get("existed") ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/hr/users/add"], \Yii::$app->request->get(), ['existed' => 1]))?>"><?=Yii::t("main","Прикрепить существующего")?></a>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (!Yii::$app->request->get("existed")) { ?>
        <div class="mb-3 mt-0 page-header"><h6><?=Yii::t("main","Авторизационные данные")?></h6></div>

        <div class="row">
            <div class="col-4">
                <div class="form-group" attribute="login">
                    <label for="login" class="control-label"><?php echo $model->getAttributeLabel("login");?></label>
                    <input class="form-control" type="text" placeholder="<?php echo $model->getAttributeLabel("login");?>" id="login" value="<?=$model->login?>" name="login" />
                </div>
            </div>
            <div class="col-4">
                <div class="form-group" attribute="email">
                    <label for="email" class="control-label"><?php echo $model->getAttributeLabel("email");?></label>
                    <input class="form-control" type="text" placeholder="<?php echo $model->getAttributeLabel("email");?>" id="email" value="<?=$model->email?>" name="email" />
                </div>
            </div>
            <div class="col-4">
                <div class="form-group" attribute="phone">
                    <label for="phone" class="control-label"><?php echo $model->getAttributeLabel("phone");?></label>
                    <input class="form-control" type="text" placeholder="<?=Yii::t("main","+7(xxx)xxxxxxx")?>" id="phone" value="<?=$model->phone?>" name="phone" />
                </div>
            </div>
        </div>

        <div class="mt-2 mb-3 page-header"><h6><?=Yii::t("main","Основные данные")?></h6></div>

        <div class="row">
            <div class="col-8">
                <div class="form-group" attribute="fio">

                    <label for="fio" class="control-label"><?php echo $model->getAttributeLabel("fio");?></label>
                    <input class="form-control" type="text" placeholder="<?php echo $model->getAttributeLabel("fio");?>" id="fio" value="<?=$model->fio?>" name="fio" />

                </div>
            </div>
            <?php if (Yii::$app->request->get("type") == \app\models\filters\UsersFilter::TYPE_STAFF) { ?>
                <div class="col-4">
                    <div class="form-group" attribute="role">

                        <label for="role" class="control-label"><?php echo $model->getAttributeLabel("role");?></label>

                        <?php $roles = \app\models\Users::getRoles(); ?>
                        <?php unset($roles['pupil']) ?>
                        <?php if (!Yii::$app->user->can("admin")) {
                            unset($roles['specialist'], $roles['admin']);
                        } ?>

                        <?=\yii\helpers\Html::dropDownList("role", $model->role, $roles, [
                            "class"=>"form-control"
                        ]);?>

                    </div>
                </div>
            <?php } ?>
        </div>

        <?=$this->render("custom", [
                'model' => $model
        ])?>

        <?php if (\Yii::$app->user->can("admin") AND $model->id) { ?>

            <div class="form-group mt-3" attribute="password">
                <label class="control-label"><?=Yii::t("main","Сменить пароль")?></label>
                <input type="password" class="form-control" name="password" placeholder="<?=Yii::t("main","Введите пароль")?>" />
            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="row">
            <div class="col-6">
                <div class="form-group" attribute="login">
                    <label for="login" class="control-label"><?php echo $model->getAttributeLabel("login");?></label>
                    <input class="form-control" type="text" placeholder="<?php echo $model->getAttributeLabel("login");?>" id="login" value="<?=$model->login?>" name="login" />
                </div>
            </div>

            <?php if (Yii::$app->request->get("type") == \app\models\filters\UsersFilter::TYPE_STAFF) { ?>
                <div class="col-6">
                    <div class="form-group" attribute="role">

                        <label for="role" class="control-label"><?php echo $model->getAttributeLabel("role");?></label>

                        <?php $roles = \app\models\Users::getRoles(); ?>
                        <?php unset($roles['pupil']) ?>
                        <?php if (!Yii::$app->user->can("admin")) {
                            unset($roles['specialist'], $roles['admin']);
                        } ?>

                        <?=\yii\helpers\Html::dropDownList("role", $model->role, $roles, [
                            "class"=>"form-control"
                        ]);?>

                    </div>
                </div>
            <?php } ?>

        </div>

        <?=$this->render("custom", [
            'model' => $model
        ])?>

    <?php } ?>

    <div class="submit-panel mt-3">

        <button type="submit" class="btn btn-success" ><?=Yii::t("main","Сохранить")?></button>

    </div>

    <?php \app\widgets\EForm\EForm::end(); ?>

</div>