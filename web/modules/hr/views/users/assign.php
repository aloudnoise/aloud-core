<?php
(Yii::$app->assetManager->getBundle("backbone"))::registerWidget($this, "EDisplayDate");
$this->addTitle(Yii::t("main","Список пользователей"));

?>

<div class="action-content">

    <?=$this->render("pupils", [
        "provider" => $provider,
        "filter" => $filter,
    ])?>

</div>
