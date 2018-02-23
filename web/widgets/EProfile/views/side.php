<div class="side-profile bg-light">

    <div class="user-info">
        <div class="image">
            <img src="<?=$model->photoUrl?>" width="48" height="48" alt="User">
        </div>
        <div class="info-container">
            <a class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="<?=\app\helpers\OrganizationUrl::to(['/hr/users/profile', 'profile_id' => $model->id])?>"><?=$model->fio?></a>
            <div class="email"><?=\Yii::$app->user->identity->roleName?></div>
        </div>
    </div>

    <div class="menu slim-scroll">
            <div class="left-menu mt-3">
                <?php

                $new_messages = Yii::$app->cache->get(\app\models\Messages::getMessagesHash(Yii::$app->user->id));
                $new_messages = $new_messages ? count($new_messages) : 0;

                if (!\Yii::$app->user->can("teacher")) {
                    $new_events = count(\app\models\Events::getCurrentEvents());
                }

                $items = [];

                $items = array_merge($items, [
                    'messages' => [
                        'url' => \app\helpers\OrganizationUrl::to(['/messages/index', 'from' => 0, 'return' => 0]),
                        'label' => Yii::t("main", "Сообщения"),
                        'new' => $new_messages,
                        'icon' => 'comment'
                    ],
                    'divider',
                    'events' => [
                        'url' => \app\helpers\OrganizationUrl::to(['/events/index', 'from' => 0, 'return' => 0]),
                        'label' => Yii::t("main", "Мероприятия"),
                        'new' => $new_events ? : false,
                        'icon' => 'calendar',
                    ],
                ]);

                if (\Yii::$app->user->can("teacher")) {
                    $items = array_merge($items, [
                        'courses' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/courses/index', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Курсы"),
                            'icon' => 'book'
                        ],
                        'library' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/library/index', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Библиотека"),
                            'icon' => 'folder-open'
                        ],
                        'tests' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/tests/base/index', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Тесты"),
                            'icon' => 'list'
                        ],
                        'tasks' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/tasks/index', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Задания"),
                            'icon' => 'file'
                        ]
                    ]);
                } else {
                    $items = array_merge($items, [
                        'courses' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/courses/assigned', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Курсы"),
                            'icon' => 'book'
                        ],
                        'library' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/library/assigned', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Библиотека"),
                            'icon' => 'folder-open'
                        ],
                        'tests' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/tests/base/assigned', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Тесты"),
                            'icon' => 'list'
                        ],
                        'tasks' => [
                            'url' => \app\helpers\OrganizationUrl::to(['/tasks/assigned', 'from' => 0, 'return' => 0]),
                            'label' => Yii::t("main", "Задания"),
                            'icon' => 'file'
                        ]
                    ]);
                }

                $items[] = 'divider';

                $items['news'] = [
                    'url' => \app\helpers\OrganizationUrl::to(['/news/index', 'from' => 0, 'return' => 0]),
                    'label' => Yii::t("main", "Новости"),
                    'icon' => 'newspaper-o'
                ];

                if (\Yii::$app->user->can("specialist")) {
                    $items['polls'] = [
                        'url' => \app\helpers\OrganizationUrl::to(['/polls/index', 'from' => 0, 'return' => 0]),
                        'label' => Yii::t("main", "Голосования"),
                        'icon' => 'list'
                    ];
                }

                if (Yii::$app->params['forum_host']) {
                    $items['forum'] = [
                        'url' => Yii::$app->params['forum_host'] . '?uid=' . Yii::$app->user->identity->getHash(),
                        'label' => Yii::t("main", "Форум"),
                        'icon' => 'users'
                    ];
                }

                if (\Yii::$app->user->can("specialist")) {
                    $items['hr'] = [
                        'url' => \app\helpers\OrganizationUrl::to(['/hr/users/index', 'from' => 0, 'return' => 0]),
                        'label' => Yii::t("main", "HR"),
                        'icon' => 'address-card'
                    ];
                }

                if (\Yii::$app->user->can("teacher")) {
                    $items['reports'] = [
                        'url' => \app\helpers\OrganizationUrl::to(['/reports/index', 'from' => 0, 'return' => 0]),
                        'label' => Yii::t("main", "Отчеты"),
                        'icon' => 'bars'
                    ];
                }

                if (\Yii::$app->user->can("admin")) {
                    $items['dics'] = [
                        'url' => \app\helpers\OrganizationUrl::to(['/dics/list', 'from' => 0, 'return' => 0]),
                        'label' => Yii::t("main", "Настройки системы"),
                        'icon' => 'gears'
                    ];
                }

                foreach ($items as $name => $item) { ?>

                    <?php if ($item == 'divider') { ?>
                        <hr />
                    <?php } else { ?>
                        <div class="menu-item">
                            <a href="<?=$item['url']?>" style="" class="px-3 py-2 menu-item d-block <?=$name?>-side-menu <?=(Yii::$app->controller->id == $name || Yii::$app->controller->module->id == $name) ? "text-primary" : "text-muted"?> ">
                                <div class="row">
                                    <div class="col-auto align-self-center">
                                        <strong><i style="text-shadow: 0px 0px 1px #777; font-size:1.3rem" class="fa fa-<?=$item['icon']?>"></i></strong>
                                    </div>
                                    <div class="col pl-2 align-self-start">
                                        <p class="text-uppercase text-left">
                                            <span class="font-weight-6"><?=$item['label']?></span>
                                        </p>
                                    </div>
                                    <div class="col-auto align-self-center ml-auto">
                                        <span class="text-danger new-count ml-auto" style="font-weight: bold;"><?=$item['new'] > 0 ? $item['new'] : ''?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <?php
            $polls = \app\models\Polls::getActiveList();
            if ($polls) {
                foreach ($polls as $poll) { ?>
                    <div class="white-block mt-2">
                        <?=$this->render("@app/views/common/poll_question", [
                            'poll' => $poll,
                            'result' => $poll->myResult,
                            'vote' => true
                        ])?>
                    </div>
                <?php }
            }
            ?>
    </div>
</div>