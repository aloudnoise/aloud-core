<?=$this->setTitle(Yii::t("main","Импорт слушателей. Шаг 2."))?>
<div class="action-content">

    <div class="white-block">

        <h5 class="mb-3"><?=Yii::t("main","Импорт слушателей. Шаг 2.")?></h5>

        <?php echo \app\widgets\EUploader\EUploader::widget([]);

        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions"=>[
                "action"=>app\helpers\OrganizationUrl::to(array_merge(["/hr/users/import"], \Yii::$app->request->get())),
                "method"=>"post",
                "id"=>"step2Form"
            ],
        ]);

        ?>

        <?php
            $data = $model->getData();
            $selected_columns = Yii::$app->cache->get("pupil_import_selected_columns");
            $fields = array_merge([
                'login' => Yii::t("main",'Идентификационный номер/Логин'),
                'fio' => Yii::t("main",'ФИО'),
                'email' => Yii::t("main","Email"),
                'phone' => Yii::t("main","Телефон")
            ], \app\helpers\ArrayHelper::map(\app\models\DicValues::findByDic("pupil_custom_fields"), "value", "nameByLang"));
        ?>
            <?php if (!empty($data)) { ?>
                <div class="imported-document">

                    <div class="alert alert-info">
                        <h6><?=Yii::t("main","{parsed} {count} {rows}", [
                                'parsed' => \common\helpers\Common::multiplier(count($data), [
                                    Yii::t("main","Распознана"),
                                    Yii::t("main","Распознано"),
                                    Yii::t("main","Распознано"),
                                ]),
                                'count' => count($data),
                                'rows' => \common\helpers\Common::multiplier(count($data), [
                                    Yii::t("main","строка"),
                                    Yii::t("main","строки"),
                                    Yii::t("main","строк"),
                                ])
                            ])?></h6>
                        <p><?=Yii::t("main","Укажите в первой строке, к каким полям относится каждая колонка. Вы можете удалить ненужные строки нажав на крестик справа.")?></p>
                    </div>

                    <div class="form-group" attribute="rows">
                        <div style="overflow-x:auto">
                            <table class="table border-0 table-bordered table-sm table-hover">
                                <tr>
                                    <th class="border-0"></th>
                                    <?php foreach ($data[0] as $i => $c) { ?>
                                        <th>
                                            <?=\app\helpers\Html::dropDownList("columns[$i]", $selected_columns[$i] ?: null, $fields, [
                                                    'prompt' => '',
                                                    'class' => 'form-control form-control-sm'
                                            ])?>
                                        </th>
                                    <?php } ?>
                                </tr>
                                <?php foreach ($data as $i=>$row) { ?>
                                    <tr>
                                        <td style="vertical-align: middle;" class="border-0">
                                            <input type="hidden" value='<?=json_encode($row)?>' name="rows[<?=$i?>]" />
                                            <a href="#" class="btn btn-outline-danger delete-row btn-sm"><i class="fa fa-times"></i></a>
                                        </td>
                                        <?php foreach ($row as $c) { ?>
                                            <td style="vertical-align: middle;"><label class="mb-0" for="row_<?=$i?>"><?=$c?></label></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>

                    <?php if (isset($fields['division'])) { ?>
                        <div class="form-group mt-3" attribute="division">
                            <label><?=Yii::t("main","Подразделение слушателей")?></label>
                            <input class="form-control" type="text" name="division" placeholder="<?=Yii::t("main","Подразделение")?>" value="" />
                            <p class="mt-2 help-block text-muted"><?=Yii::t("main","Укажите если все слушатели в списке принадлежат к одному подразделению")?></p>
                        </div>
                    <?php } ?>

                    <div class="form-group mt-3" attribute="group">
                        <label><?=Yii::t("main","Создать для данных слушателей группу под названием")?></label>
                        <input class="form-control" type="text" name="group" value="<?=Yii::t("main","Импорт слушателей от {date}", [
                                "date" => date('d.m.Y H:i')
                            ])?>" />
                    </div>


                </div>


            <?php } else { ?>
                <div class="alert alert-danger"><?=Yii::t("main","Не удалось распознать данный файл")?></div>
            <?php } ?>




        <div class="form-group mb-0">

            <?php if (!empty($data)) { ?>
                <button type="submit" class="pointer btn btn-primary"><?=Yii::t("main","Импортировать")?></button>
            <?php } ?>
            <a href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/import'])?>" class="btn btn-warning"><?=Yii::t("main","Назад")?></a>
            <a href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/index', 'type' => \app\models\filters\UsersFilter::TYPE_PUPILS])?>" class="btn btn-danger"><?=Yii::t("main","Отмена")?></a>

        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>


</div>