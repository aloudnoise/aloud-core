<?php
namespace common\models;


use common\components\Model;

class Instructions extends Model
{

    public static function getInstruction($type) {

        $baseUrl = \Yii::$app->assetManager->getBundle("base")->baseUrl;

        $instructions = [
            'ru-RU' => [
                'main' => [
                    'label' => \Yii::t("main","Главная").".doc",
                    'url' => $baseUrl."/other/instructions/ru-RU/main.doc",
                ],
                'forum' => [
                    'label' => \Yii::t("main","Форум").".doc",
                    'url' => $baseUrl."/other/instructions/ru-RU/forum.doc",
                ],
                'hr' => [
                    'label' => \Yii::t("main","HR").".doc",
                    'url' => $baseUrl."/other/instructions/ru-RU/hr.doc",
                ],
                'news' => [
                    'label' => \Yii::t("main","Новости").".doc",
                    'url' => $baseUrl."/other/instructions/ru-RU/news.doc",
                ],
                'polls' => [
                    'label' => \Yii::t("main","Голосования").".doc",
                    'url' => $baseUrl."/other/instructions/ru-RU/polls.doc",
                ],
                'dics' => [
                    'label' => \Yii::t("main","Настройки системы").".doc",
                    'url' => $baseUrl."/other/instructions/ru-RU/dics.doc",
                ],
                'tasks' => [
                    'label' => \Yii::t("main","Задания").".docx",
                    'url' => $baseUrl."/other/instructions/ru-RU/tasks.docx",
                ],
                'courses' => [
                    'label' => \Yii::t("main","Курсы").".docx",
                    'url' => $baseUrl."/other/instructions/ru-RU/courses.docx",
                ],
                'library' => [
                    'label' => \Yii::t("main","Материалы").".docx",
                    'url' => $baseUrl."/other/instructions/ru-RU/materials.docx",
                ],
                'tests' => [
                    'label' => \Yii::t("main","Тесты").".docx",
                    'url' => $baseUrl."/other/instructions/ru-RU/tests.docx",
                ],
                'events' => [
                    'label' => \Yii::t("main","Мероприятия").".docx",
                    'url' => $baseUrl."/other/instructions/ru-RU/events.docx",
                ],
                'messages' => [
                    'label' => \Yii::t("main","Сообщения").".docx",
                    'url' => $baseUrl."/other/instructions/ru-RU/messages.docx",
                ],
                'reports' => [
                    'label' => \Yii::t("main","Отчеты").".docx",
                    'url' => $baseUrl."/other/instructions/ru-RU/reports.docx",
                ],
            ],
            'kk-KZ' => [
                'main' => [
                    'label' => \Yii::t("main","Главная").".doc",
                    'url' => $baseUrl."/other/instructions/kk-KZ/main.doc",
                ],
                'news' => [
                    'label' => \Yii::t("main","Новости").".doc",
                    'url' => $baseUrl."/other/instructions/kk-KZ/news.doc",
                ],
                'hr' => [
                    'label' => \Yii::t("main","HR").".doc",
                    'url' => $baseUrl."/other/instructions/kk-KZ/hr.doc",
                ],
                'polls' => [
                    'label' => \Yii::t("main","Голосования").".doc",
                    'url' => $baseUrl."/other/instructions/kk-KZ/polls.doc",
                ],
                'dics' => [
                    'label' => \Yii::t("main","Настройки системы").".doc",
                    'url' => $baseUrl."/other/instructions/kk-KZ/dics.doc",
                ],
            ]
        ];

        return $instructions[\Yii::$app->language][$type] ?: null;

    }

}