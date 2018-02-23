<div class="events-background-block background-block bg-info border-warning" style="border-top:5px solid;">
    <div class="block-bg"></div>
    <div class="inner h-100 p-4">
        <div class="row h-100">
            <div class="col align-self-end">
                <h3 class="text-white">
                    <div class="row">
                        <?=Yii::t("main","<div class='col col-auto'>Мероприятия на </div> {month} {year}", [
                            'month' => $this->render("calendar/_month", [
                                'filter' => $filter
                            ]),
                            'year' => $this->render("calendar/_year", [
                                'filter' => $filter
                            ])
                        ])?>
                    </div>
                </h3>
            </div>
        </div>
    </div>
</div>
<div class="white-block p-4 mt-3">

    <?=\app\widgets\ECalendar\ECalendar::widget([
        'month' => $filter['month'],
        'year' => $filter['year'],
        'day' => $filter['day'],
        'route' => '/events/index',
        'models' => $events
    ])?>

</div>
