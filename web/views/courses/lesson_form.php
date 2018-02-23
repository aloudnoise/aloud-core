<?php
$this->setTitle(Yii::t("main","Новый урок"), false);
(Yii::$app->assetManager->getBundle("ckeditor"))::register($this);
?>

<div class="action-content">

    <div class="white-block mt-3">
<!--    <ul class="nav nav-tabs mb-3">-->
<!--        <li class="nav-item"><a class="nav-link mactive">--><?//=Yii::t("main","Текст")?><!--</a></li>-->
<!--        <li class="nav-item"><a class="nav-link"href="--><?//= \app\helpers\OrganizationUrl::to(['/library/index', "filter"=>[
//                    "type"=>[
//                        \app\models\Materials::TYPE_DER
//                    ]
//                ], "from" => (new \common\models\From(['lesson', Yii::$app->request->get("cid")]))->params) ?><!--">--><?//=Yii::t("main","ЦОР")?><!--</a></li>-->
<!--    </ul>-->
<!---->

        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions"=>[
                "action"=>\app\helpers\OrganizationUrl::to(array_merge(["/courses/view"], \Yii::$app->request->get())),
                "method"=>"post",
                "id"=>"newCourseLessonForm"
            ],
        ]);
        ?>

        <?php
        echo \app\widgets\EUploader\EUploader::widget([
            "standalone" => true
        ]);
        (Yii::$app->assetManager->getBundle("ckeditor"))::initiateUploader();
        ?>

        <div class="form-group mb-2" attribute="name">
            <input type="text" name="name" class="form-control" placeholder="<?=Yii::t("main","Название урока")?>" value="<?=$model->name?>" />
        </div>

        <div class="form-group"  attribute="content">
            <?=\app\helpers\Html::textarea("content", $model->content, [
                'id' => 'content',
                'textareatype'=>'ckeditor',
                'cktype' => 'full',
                "class"=>"form-control lesson-textarea",
                "placeholder"=>Yii::t("main","Содержание урока"),
                'rows' => '10'
            ])?>
        </div>

        <div class="submit-panel mt-3">
            <button type="submit" class="btn btn-primary"><?=Yii::t("main","Сохранить")?></button>
            <a class="btn ml-1 btn-danger" href="<?=\app\helpers\OrganizationUrl::to(['/courses/view', 'id' => $model->course_id])?>"><?=Yii::t("main","Отмена")?></a>
        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>
</div>