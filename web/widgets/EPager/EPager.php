<?php

namespace aloud_core\web\widgets\EPager;

use aloud_core\web\helpers\Html;
use yii\widgets\LinkPager;

// TODO REWORK

class EPager extends LinkPager {

    const CSS_FIRST_PAGE='first';
    const CSS_LAST_PAGE='last';
    const CSS_PREVIOUS_PAGE='previous';
    const CSS_NEXT_PAGE='next';
    const CSS_INTERNAL_PAGE='page';
    const CSS_HIDDEN_PAGE='disabled';
    const CSS_SELECTED_PAGE='active';

    public $prevPageCssClass = 'page-item';
    public $disabledPageCssClass = 'hidden';
    public $nextPageCssClass = 'page-item';
    public $pageCssClass = "page-item";
    public $linkOptions = [
        "class" => "page-link"
    ];

    public function init()
    {
        if(!isset($this->options['class']))
            $this->options['class']='pagination';
        return parent::init();
    }
}
?>