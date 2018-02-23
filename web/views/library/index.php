<?php
use app\models\Materials;

$this->setTitle(Yii::t("main","Библиотека"), false);
?>
<div class="action-content">
    <div class="row big-gutter">

        <div class="col-9">

            <div class="row">
                <div class="col">
                    <h3 class="mb-0 font-weight-4"><?=Yii::t("main","Библиотека")?></h3>
                </div>
                <?php if (Yii::$app->user->can("base_teacher")) { ?>
                    <div class="col col-auto ml-auto">
                        <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/library/add"])?>" class="btn btn-primary"><?=Yii::t("main","Добавить материал")?></a>
                    </div>
                <?php } ?>
            </div>

            <div class="white-block mt-3">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted"><?=Yii::t("main","В этом списке вы можете увидеть полный перечень материалов")?></p>
                        <?php if (Yii::$app->user->can("base_teacher")) { ?>
                        <p  class="lh-1 text-muted mt-2"><?=Yii::t("main","Для создания нового материала, нажмите кнопку {add_link}, и заполните необходимые поля: тип материала, название и прочее. Вы можете добавлять как документы, так и видео с youtube, а также просто ссылки на интересные источники", [
                                'add_link' => "<a  target=\"modal\" class='text-warning' href='".\app\helpers\OrganizationUrl::to(["/library/add"])."'>".Yii::t("main","Добавить материал")."</a>"
                            ])?></p>
                        <?php } ?>
                    </div>
                </div>

                <hr />

                <div class="form-group mb-0">
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

                <?=$this->render("nav", [
                        'filter' => $filter
                ])?>

                <?php if ($provider->totalCount > 0) { ?>

                    <small>
                        <table class="table table-sm mt-3">
                            <tr>
                                <th style="vertical-align: middle;">№</th>
                                <th style="vertical-align: middle;" colspan="2"><?=Yii::t("main","Наименование")?></th>
                                <th style="vertical-align: middle;"><?=Yii::t("main","Тема")?></th>
                                <th style="vertical-align: middle;"><?=Yii::t("main","Ключевые слова")?></th>
                                <th style="vertical-align: middle;"><?=Yii::t("main","Дата добавления")?></th>
                                <th style="vertical-align: middle;"><?=Yii::t("main","Статус")?></th>
                            </tr>
                            <?php
                            $materials = $provider->getModels();
                            $number = 1;
                            foreach ($materials as $m) {
                                echo \app\widgets\EMaterial\EMaterial::widget([
                                    "backbone" => false,
                                    "type" => "row",
                                    "model" => $m,
                                    "link" => [
                                        "target" => "modal"
                                    ],
                                    'number' => $number
                                ]);
                                $number++;
                            }
                            ?>
                        </table>
                    </small>

                    <div class="mt-3">
                        <?= \app\widgets\EPager\EPager::widget([
                            'pagination' => $provider->pagination,
                        ]) ?>
                    </div>

                <?php } else { ?>
                    <div class="mt-2 mb-0 alert alert-warning"><h5><?= Yii::t("main", "Материалов не найдено") ?></h5></div>
                <?php } ?>

            </div>

        </div>

        <div class="col-3">
            <div class="row">
                <div class="col">
                    <h3 class="mb-0 font-weight-4"><?=Yii::t("main","Фильтры")?></h3>
                </div>
            </div>
            <div class="mt-3 white-block filter-panel">
                <div class="form-group mb-3">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <?=\app\helpers\Html::dropDownList("type", $filter['type'], [
                                Materials::TYPE_LINK => Yii::t("main", "Ссылка"),
                                Materials::TYPE_VIDEO => Yii::t("main", "Видео"),
                                Materials::TYPE_FILE => Yii::t("main", "Файл"),
                                Materials::TYPE_DER => Yii::t("main", "ЦОР"),
                            ], [
                                'data-role' => 'filter',
                                'data-action' => 'input',
                                'class' => 'form-control',
                                'prompt' => Yii::t("main", "Тип"),
                            ])?>
                        </div>
                        <div class="col-12 mb-3">
                            <input data-role="filter" data-action="input" placeholder="<?= Yii::t("main", "Тема") ?>" type="text" value="<?= $filter->theme ?>" class="form-control"
                                   name="theme"/>
                        </div>
                        <div class="col-6">
                            <a href="#" data-role="filter" data-action="submit" class="btn-sm btn-block btn btn-outline-primary"><?= Yii::t("main", "Найти") ?></a>
                        </div>
                        <div class="col-6">
                            <a href="#" data-role="filter" data-action="reset" class="btn-sm btn btn-block btn-outline-danger"><?= Yii::t("main", "Сбросить") ?></a>
                        </div>
                    </div>
                </div>

                <?=$this->render("@app/views/common/tags", [
                    'tags' => $tags,
                    'route' => '/library/index'
                ])?>

            </div>
        </div>

    </div>

</div>