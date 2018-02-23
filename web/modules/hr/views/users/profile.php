<?php $this->setTitle($model->fio)?>
<div class="action-content">

    <div class="row">
        <div class="col col-auto">

            <?php
            if ($model->canEdit) {
                $f = \app\widgets\EForm\EForm::begin([
                    "htmlOptions"=>[
                        "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/hr/users/profile"], \Yii::$app->request->get())),
                        "method"=>"post",
                        "id"=>"profileForm"
                    ],
                ]);
                ?>

                <?php

                echo \app\widgets\EUploader\EUploader::widget([
                        'standalone' => true
                ]);
                echo \app\widgets\ECropper\ECropper::widget([
                ]);
            ?>

            <script type="text/template" id="file_template">
                <div class="attached-file" style="margin-bottom:15px;">
                    <input type="hidden" value='<%=data.model.get("photo"))%>' name="uploaded"/>
                    <a target='_blank' href="<%=data.model.get("url")%>"><%=data.model.get("name")%></a>
                    <a style="margin-left:15px; cursor:pointer;" class="delete-file btn-link">&times;</a>
                </div>
            </script>

            <script id="attached_file_template" type="text/template">
                <div class='uploaded-file'>
                    <% if (!data.error && data.percent>0 && data.percent < 100) { %>
                    <div style='margin-top:5px; margin-bottom:5px;' class="progress progress-striped">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                             aria-valuemax="100" style="width: <%=data.percent%>%">
                            <span><%=(Math.ceil(data.loaded/1024)) + "kb" %>/<%=(Math.ceil(data.total/1024)) + "kb"%></span>
                        </div>
                    </div>
                    <% } %>
                </div>
            </script>
            <?php } ?>

            <div class="white-block border-warning">
                <div class="uploader text-center">
                    <div class="uploaded-photo">
                        <img style="max-width:150px;"
                             alt="<?=Yii::t('main', 'Изображение пользователя')?>" src="<?=$model->photoUrl?>">
                    </div>
                    <?php if ($model->canEdit) { ?>
                        <input type="hidden" name="photo" id="photo" value="<?=$model->photo?>"/>
                        <a style="color:#fff; cursor:pointer; margin-top:-30px;" class="btn btn-sm btn-primary upload-button">
                            <i class="fa fa-photo"></i></a>
                        <input style="display:none" type="file" name="file"/>
                    <?php } ?>
                </div>

                <div class="mt-3">
                    <p class="text-center">
                        <?=$model->fio?>
                    </p>
                </div>

                <?php if ($model->canEdit) { ?>
                    <div class="mt-5">
                        <button type="submit" class="btn-block btn btn-success"><?=Yii::t("main","Сохранить")?></button>
                    </div>
                <?php } ?>

            </div>

            <?php if ($model->canEdit) { ?>
                <?php \app\widgets\EForm\EForm::end(); ?>
            <?php } ?>

        </div>

        <div class="col">

            <div class="white-block border-warning">
                <div class="page-header"><h5><?=Yii::t("main","История тестирования")?></h5></div>

                <?php if ($test_results) { ?>
                <small>
                    <table class="table table-bordered mt-2 table-sm">
                        <tr>
                            <th><?=Yii::t("main","Тест")?></th>
                            <th><?=Yii::t("main","Дата")?></th>
                            <th><?=Yii::t("main","Время прохождения")?></th>
                            <th><?=Yii::t("main","Правильных ответов")?></th>
                            <th colspan="1"><?=Yii::t("main","Результат")?></th>
                        </tr>

                        <?php
                        foreach ($test_results as $tr) { ?>
                            <tr>
                                <td><?=$tr->test->name?></td>
                                <td><?=\app\widgets\EDisplayDate\EDisplayDate::widget([
                                        "time" => $tr->ts,
                                        "formatType" => 2
                                    ])?>
                                </td>
                                <td><?=floor((strtotime($tr->finished) - strtotime($tr->ts))/60)."м. ".((strtotime($tr->finished) - strtotime($tr->ts)) % 60)."с. "?></td>
                                <td><?=$tr->correct_answers." из ".$tr->test->qcount?></td>
                                <td><b class="text-<?=$tr->resultTextColor?>"><?=$tr->translatedResultText?></b>
                                    <?php if (Yii::$app->user->can('base_teacher')) { ?>
                                        <a target="modal" href="<?=\app\helpers\OrganizationUrl::to(["/tests/process/result", 'id' => $tr->id])?>"><i class="fa fa-eye"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                </small>
                <?php } else { ?>
                    <div class="alert alert-warning mt-2"><?=Yii::t("main","Пока не пройдено ни одного тестирования")?></div>
                <?php } ?>

            </div>

            <div class="white-block border-warning mt-1">
                <div class="page-header"><h5><?=Yii::t("main","Просмотренные курсы")?></h5></div>
                <?php if ($courses) { ?>
                    <?php foreach ($courses as $course) { ?>
                        <div class="mt-3">
                            <?php
                            echo \app\widgets\ECourse\ECourse::widget([
                                "model" => $course,
                                "backbone" => false,
                            ]);
                            ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-warning mt-2"><?=Yii::t("main","Пока не просмотрено ни одного курса")?></div>
                <?php } ?>
            </div>

        </div>

        <div class="col">

            <div class="white-block border-warning">
                <div class="page-header"><h5><?=Yii::t("main","Просмотренные материалы")?></h5></div>
                <?php if ($materials) { ?>
                    <?php foreach ($materials as $material) { ?>
                        <div class="mt-3">
                            <?php
                            echo \app\widgets\EMaterial\EMaterial::widget([
                                "model" => $material,
                                "backbone" => false,
                                "type" => "media",
                            ]);
                            ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-warning mt-2"><?=Yii::t("main","Пока не просмотрено ни одного материала")?></div>
                <?php } ?>
            </div>

            <div class="white-block border-warning mt-1">
                <div class="page-header"><h5><?=Yii::t("main","Скачанные материалы")?></h5></div>
                <?php if ($d_materials) { ?>
                    <?php foreach ($d_materials as $material) { ?>
                        <div class="mt-3">
                            <?php
                            echo \app\widgets\EMaterial\EMaterial::widget([
                                "model" => $material,
                                "backbone" => false,
                                "type" => "media",
                            ]);
                            ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-warning mt-2"><?=Yii::t("main","Пока не скачано ни одного материала")?></div>
                <?php } ?>
            </div>

        </div>

    </div>

</div>