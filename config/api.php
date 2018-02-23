<?php

$params = require __DIR__ . '/constants.php';

$config = [
    'id' => 'LMS_API',
    'basePath' => dirname(__DIR__). '/api',
    'aliases' => [
        '@api' => dirname(__DIR__) . '/api',
        '@common' => dirname(__DIR__) . '/common',
        '@app' => dirname(__DIR__) . '/web'
    ],
    'controllerNamespace' => 'api\\controllers',
    'bootstrap' => ['urlManager'],
    'language' => 'ru-RU',
    'modules' => require __DIR__. '/api/modules.php',
    'components' => [
        'request' => [
            'cookieValidationKey' => $params['secret_word'],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // required for POST input via `php://input`
            ],
        ],
        'response' => [
            'format' =>  \yii\web\Response::FORMAT_JSON,
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'api\components\JsonGzipFormatter'
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'api\models\Users',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'authManager' => [
            'class' => 'common\components\PhpManager',
        ],
        'cache' => [
            'class' => 'common\components\DummyCache'
        ],
        'urlManager' => [
            'class' =>'common\components\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName'=>false,
            'rules' => array_merge(
                require __DIR__. '/api/rules.php'
            ),
        ],
        'db' => require __DIR__ . '/db.php',
        'languageDetector' => [
            'class' => 'api\components\LanguageDetector',
        ],
        'i18n' => [
            'translations' => [
                'main*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@app/messages/"
                ],
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection'
        ],
        'bbb' => [
            'class' => 'common\components\BigBlueButton',
        ],
    ],
    'params' => $params,
];

if (YII_DEBUG) {
    $config['bootstrap'] = array_merge(['log'], $config['bootstrap']);
    $config['components']['log'] = [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@api/runtime/logs/all.log'

            ],
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['info'],
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@api/runtime/logs/trace.log',
                'logVars' => [],
                'levels' => ['trace'],
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@api/runtime/logs/error.log',
                'levels' => ['error'],
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@api/runtime/logs/db.log',
                'logVars' => [],
                'levels' => ['profile'],
                'categories' => ['yii\db\Command::query'],
                'prefix' => function($message) {
                    return '';
                }
            ]
        ],
    ];
}

return $config;
