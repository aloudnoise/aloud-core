<?php
namespace common\traits;

use common\models\counters\Downloads;
use common\models\counters\Views;

trait CounterTrait
{
    public function getViews()
    {
        return $this->hasOne(Views::className(), ['record_id' => 'id'])->where(['table' => $this->tableName()])->from("counters.views_count");
    }

    public function getViewsCount()
    {
        return $this->views->count ? : 0;
    }

    public function addView()
    {
        Views::add($this);
    }

    public function getDownloads()
    {
        return $this->hasOne(Downloads::className(), ['record_id' => 'id'])->where(['table' => $this->tableName()])->from("counters.downloads_count");
    }

    public function getDownloadsCount()
    {
        return $this->downloads->count ? : 0;
    }

    public function addDownload()
    {
        Downloads::add($this);
    }
}