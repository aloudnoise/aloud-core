<?=$this->setTitle(Yii::t("main","Импорт слушателей. Шаг 1."))?>
<div class="action-content">

    <div class="white-block">

        <h5><?=Yii::t("main","Импорт слушателей. Шаг 1.")?></h5>
        <p class="mt-1"><?=Yii::t("main","Загрузите Excel документ со списком слушателей и нажмите кнопку \"Далее\"")?></p>

        <div class="import-uploader-block mt-3"></div>


        <?php echo \app\widgets\EUploader\EUploader::widget([]);

        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions"=>[
                "action"=>app\helpers\OrganizationUrl::to(array_merge(["/hr/users/import"], \Yii::$app->request->get())),
                "method"=>"get",
                "id"=>"step1Form"
            ],
        ]);
        ?>

        <input type="hidden" name="step" value="2" />
        <input type="hidden" name="document" id="import_document" />

        <div class="form-group mb-0 mt-3">

            <button type="submit" class="pointer btn btn-primary"><?=Yii::t("main","Далее")?></button>
            <a href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/index', 'type' => \app\models\filters\UsersFilter::TYPE_PUPILS])?>" class="btn btn-danger"><?=Yii::t("main","Отмена")?></a>

        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>
    </div>


</div>