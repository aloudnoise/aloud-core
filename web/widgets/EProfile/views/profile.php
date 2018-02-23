<a <?=$this->context->link !== false ? "href='".\app\helpers\OrganizationUrl::to(["/hr/users/profile", "profile_id"=>$this->context->model->id])."'" : ""?> style="display: block; text-decoration: none !important;">
	<div class="row">
        <div class="col-auto align-self-center pr-1">
            <div class="avatar">
                <?=\app\helpers\Html::userImg($this->context->model->photoUrl, ["style" => ""])?>
            </div>
        </div>
        <div class="col align-self-center pl-1">
            <p class="mb-0 text-light-gray"><strong><?=$this->context->model->fio?></strong></p>
            <p class="mb-0 text-very-light-gray"><?=$this->context->model->roleName?></p>
        </div>
    </div>
</a>