<div class="row">
    <div class="col">
        <h3>
            <div class="row">
                <?=Yii::t("main","<div class='col col-auto'>Учебные планы на </div> {year}", [
                    'year' => $this->render("calendar/_year", [
                        'filter' => $filter
                    ])
                ])?>
            </div>
        </h3>
    </div>
</div>

<div class="white-block mt-3 pl-5 pr-5 pb-4">
    <?=\app\widgets\ECalendar\ECalendar::widget([
        'month' => $filter['month'],
        'year' => $filter['year'],
        'route' => '/plans/index',
        'models' => $plans,
        'display' => \app\widgets\ECalendar\ECalendar::DISPLAY_MONTHS
    ])?>
</div>
