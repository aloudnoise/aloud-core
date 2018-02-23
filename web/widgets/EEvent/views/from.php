<div class="row big-gutter">
    <div class="col-12 mb-4 d-print-none">
        <div class="side-event white-block">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <a href="<?=\app\helpers\OrganizationUrl::to(["/events/view", "id" => $this->context->model->id, 'from' => 0, 'return' =>0])?>" class="icon-circle img-icon icon-circle-lg bg-info"><i class="icon-1"></i></a>
                </div>
                <div class="col pl-3 align-self-center">
                    <h4><a href="<?=\app\helpers\OrganizationUrl::to(["/events/view", "id" => $this->context->model->id, 'from' => 0, 'return' =>0])?>"><?php echo $this->context->model->name ?></a></h4>
                </div>
                <div class="col-auto ml-auto align-self-center">
                    <a href="<?=\app\helpers\OrganizationUrl::to(['/events/view', 'id' => $this->context->model->id, 'from' => 0, 'return' => 0])?>" class="btn btn-outline-danger btn-sm"><i class="fa fa-arrow-right"></i> <?=Yii::t("main","Назад")?></a>
                </div>
            </div>

        </div>
    </div>
    <div class="col">
        <?=$this->context->content?>
    </div>
</div>