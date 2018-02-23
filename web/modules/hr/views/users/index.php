<?php
(Yii::$app->assetManager->getBundle("backbone"))::registerWidget($this, "EDisplayDate");
$this->addTitle(Yii::t("main","Список пользователей"));

?>

<div class="action-content">

    <div class="row">

        <div class="col">

            <div class="row">
                <div class="col">
                    <?php
                    $get = \Yii::$app->request->get();
                    $get['filter'] = [
                        'type' => \app\models\filters\UsersFilter::TYPE_PUPILS
                    ]
                    ?>
                    <a class="btn btn-light btn-lg font-weight-6 <?=$filter->type == \app\models\filters\UsersFilter::TYPE_PUPILS ? "text-warning" : "text-muted" ?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/hr/users/index"], $get))?>"><?=Yii::t("main","Слушатели")?></a>
                    <?php
                    $get['filter'] = [
                        'type' => \app\models\filters\UsersFilter::TYPE_STAFF
                    ]
                    ?>
                    <a class="btn btn-light btn-lg font-weight-6 <?=$filter->type == \app\models\filters\UsersFilter::TYPE_STAFF ? "text-warning" : "text-muted"?>" href="<?=\app\helpers\OrganizationUrl::to(array_merge(["/hr/users/index"],$get))?>"><?=Yii::t("main","Сотрудники")?></a>
                    <a class="btn btn-light btn-lg font-weight-6 text-muted" href="<?=\app\helpers\OrganizationUrl::to(["/hr/groups/index"])?>"><?=Yii::t("main","Группы")?></a>
                </div>
            </div>

            <?php
                $views = [
                    \app\models\filters\UsersFilter::TYPE_PUPILS => 'pupils',
                    \app\models\filters\UsersFilter::TYPE_STAFF => 'staff'
                ]
            ?>

            <?=$this->render($views[$filter->type], [
                "provider" => $provider,
                "filter" => $filter,
            ])?>

        </div>

    </div>

</div>
