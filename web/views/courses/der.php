<?php
$this->setTitle($der->name, false);
?>

<div class="action-content der-content">

    <div class="text-content material-content">
        <?php
        echo \app\widgets\EMaterial\EMaterialInfo::widget([
            "type" => "big",
            "material" => $der,
            "tincan_query_string" => "&activity_id=course_".$lesson->course_id
        ])
        ?>
    </div>

</div>