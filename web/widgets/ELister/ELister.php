<?php

namespace aloud_core\web\widgets\ELister;
use yii\widgets\ListView;

// TODO REWORK

class ELister extends ListView
{
    public $pager = [
        "class"=>'\aloud_core\web\widgets\EPager\EPager',
        'firstPageLabel'=>'<<',
        'prevPageLabel'=>'<',
        'nextPageLabel'=>'>',
        'lastPageLabel'=>'>>',
        'maxButtonCount'=>'6',
        "header"=>"",
    ];
    public $pagerCssClass = "EPager";
}
?>