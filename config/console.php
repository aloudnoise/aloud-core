<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'aliases' => [
        '@common' => dirname(__DIR__) . '/common',
        '@commands' => dirname(__DIR__). '/commands',
        '@app' => dirname(__DIR__) . '/web'
    ],
    'bootstrap' => ['log'],
    'controllerNamespace' => 'commands',
    'components' => [
        'cache' => [
            'class' => 'common\components\DummyCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require __DIR__ . '/db.php',
        'data' => [
            'class' => 'app\components\JModel'
        ],
        'i18n' => [
            'translations' => [
                'main*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@app/messages/"
                ],
            ],
        ],
        'authManager' => [
            'class' => 'common\components\PhpManager',
        ],
        'bbb' => [
            'class' => 'common\components\BigBlueButton',
        ],
        'rabbit' => [
            'class' => 'common\components\Rabbit',
            'host' => $params['rabbit']['host'],
            'port' => $params['rabbit']['port'],
            'user' => $params['rabbit']['user'],
            'password' => $params['rabbit']['password']
        ],
        'redis' => $params['redis'],
        'urlManager' => [
            'baseUrl' => $params['host'],
            'class' => 'common\components\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

                '<oid:\d+>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<oid:\d+>/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<oid:\d+>/<module:\w+>/<module2:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<module2>/<controller>/<action>',
                '<oid:\d+>/<module:\w+>/<module2:\w+>/<module3:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<module2>/<module3>/<controller>/<action>',
                '<oid:\d+>/<module:\w+>/<module2:\w+>/<module3:\w+>/<module4:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<module2>/<module3>/<module4>/<controller>/<action>',

                '<module:\w+>/<controller:\w+>'=>'<module>/<controller>',
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>/*'=>'<module>/<controller>/<action>',

                '<controller:\w+>'=>'<controller>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => '@app/../migrations',
            'templateFile' => '@common/components/migration_template.php',
        ],
    ],
    'params' => $params,
];


if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    // Faker ext.
    $config['controllerMap']['fixture'] = [
            'class' => 'yii\faker\FixtureController',
            'fixtureDataPath' => '@tests/fixtures/data',
            'templatePath' => '@tests/fixtures/templates/fixtures',
            'namespace' => 'tests\fixtures',
    ];
}

return $config;
