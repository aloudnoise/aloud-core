<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 21.12.2017
 * Time: 0:48
 */

namespace app\widgets\ECalendar;


use app\components\Widget;

class ECalendar extends Widget
{

    const DISPLAY_DAYS = 'days';
    const DISPLAY_MONTHS = 'months';

    public $day = null;
    public $month = null;
    public $year = null;
    public $route = null;

    public $display = self::DISPLAY_DAYS;

    public $models = [];

    public function run()
    {
        return $this->render($this->display, [
            "month" => $this->month ?: ($this->display == self::DISPLAY_DAYS ? date('n') : null),
            "year" => $this->year ?: date('Y'),
            "route" => $this->route,
            "models" => $this->models,
            "day" => $this->day,
            'display' => $this->display,
        ]);

    }

}