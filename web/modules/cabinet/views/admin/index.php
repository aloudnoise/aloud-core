<?php
use yii\grid\GridView;

$this->title = Yii::t('main', 'Кабинет администратора');
?>
<div class="action-content">
    <div class="white-block">
        <?php foreach ($data as $organization) { ?>
            <h3 class="alert alert-info">
                <?= $organization['name'] ?>
            </h3>
            <?php echo GridView::widget([
                'dataProvider' => $organization['counters'],
                'columns' => [
                    [
                        'attribute' => 'tests',
                        'header' => Yii::t('main', 'Создано тестов'),
                    ],
                    [
                        'attribute' => 'events',
                        'header' => Yii::t('main', 'Создано мероприятий'),
                    ],
                    [
                        'attribute' => 'events_planned',
                        'header' => Yii::t('main', 'Запланировано мероприятий'),
                    ],
                    [
                        'attribute' => 'test_results',
                        'header' => Yii::t('main', 'Результатов тестирования'),
                    ],
                ],
                'layout' => "{items}\n{pager}",
            ]);
            echo GridView::widget([
                'dataProvider' => $organization['teachers'],
                'columns' => [
                    [
                        'attribute' => 'fio',
                        'header' => Yii::t('main', 'Преподаватели'),
                    ],
                    [
                        'attribute' => 'tests_count',
                        'header' => Yii::t('main', 'Создано тестов'),
                    ],
                    [
                        'attribute' => 'events_count',
                        'header' => Yii::t('main', 'Создано мероприятий'),
                    ],
                    [
                        'attribute' => 'results_count',
                        'header' => Yii::t('main', 'Результатов тестирования'),
                    ],
                ],
                'layout' => "{items}\n{pager}",
            ]);
        } ?>
    </div>
</div>