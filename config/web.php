<?php

$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

$config = [
    'id' => 'app',
    'name' => 'SDOT',
    'basePath' => dirname(__DIR__) . '/web',
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'aliases' => [
        '@common' => dirname(__DIR__) . '/common',
        '@api' => dirname(__DIR__) . '/api'
    ],
    'bootstrap' => ['log', 'urlManager', 'languageSetter'],
    'defaultRoute' => 'main/index',
    'language' => 'ru-RU',
    'timeZone' => 'Asia/Almaty',
    'modules' => [
        'cabinet' => [
            'class' => 'app\modules\cabinet\Module'
        ],
        'hr' => [
            'class' => 'app\modules\hr\Module'
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module'
        ],
        'tests' => [
            'class' => 'app\modules\tests\Module'
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $params['secret_word'],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // required for POST input via `php://input`
            ]
        ],
        'cache' => [
            'class' => 'common\components\RedisCache',
            'keyPrefix' => 'krw_cache:'
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'loginUrl' => '/auth/login',
            'enableAutoLogin' => true,
            'authTimeout' =>  86400
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 86400,
            'cookieParams' => ['lifetime' => 7 * 24 *60 * 60]
        ],
        'authManager' => [
            'class' => 'common\components\PhpManager',
        ],
        'urlManager' => [
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,     // В дебаге письмы складываются в protected/web/runtime/mail
            'transport' => $params['mailer'],
        ],
        /*'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ]
            ],
        ], */
        'db' => require __DIR__ . '/db.php',
        'data' => [
            'class' => 'app\components\BackboneControllerInformation'
        ],
        'response' => [
            'class' => 'app\components\Response'
        ],
        'i18n' => [
            'translations' => [
                'main*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@app/messages/"
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'jquery' => [
                    'class' => 'app\bundles\jquery\JQueryBundle'
                ],
                'bootstrap' => [
                    'class' => 'app\bundles\bootstrap\BootstrapBundle'
                ],
                'backbone' => [
                    'class' => 'app\bundles\backbone\BackboneBundle'
                ],
                'base' => [
                    'class' => 'app\bundles\base\BaseBundle'
                ],
                'theme' => [
                    'class' => 'app\bundles\theme\ThemeBundle'
                ],
                'tools' => [
                    'class' => 'app\bundles\tools\ToolsBundle'
                ],
                'jstrans' => [
                    'class' => 'app\bundles\jstrans\JSTransBundle'
                ],
                'urlmanager' => [
                    'class' => 'app\bundles\urlmanager\UrlManagerBundle'
                ],
                'font_awesome' => [
                    'class' => 'app\bundles\font_awesome\FontAwesomeBundle'
                ],
                'ckeditor' => [
                    'class' => 'app\bundles\ckeditor_new\CKEditorBundle'
                ],
                'yii\web\JqueryAsset' => false
            ]
        ],
        'tincan' => [
            'class' => 'common\components\TinCanVendor',
            'endpoint' => $params['tincan']['endpoint'],
            'version' => $params['tincan']['version'],
            'username' => $params['tincan']['username'],
            'password' => $params['tincan']['password']
        ],
        'rabbit' => [
            'class' => 'common\components\Rabbit',
            'host' => $params['rabbit']['host'],
            'port' => $params['rabbit']['port'],
            'user' => $params['rabbit']['user'],
            'password' => $params['rabbit']['password']
        ],
        'bbb' => [
            'class' => 'common\components\BigBlueButton',
        ],
        'view' => [
            'class' => 'app\components\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                        'debug' => true,
                    ],
                    'globals' => [
                        'html' => '\yii\helpers\Html',
                        'url' => '\yii\helpers\Url',
                    ],
                    'filters'    => [
                        'dump'       => '\yii\helpers\BaseVarDumper::dump'
                    ],
                    'functions' => [
                        'translate' => function ($category, $message, $params = []) {
                            return \Yii::t($category, $message, $params);
                        },
                        'getBundleUrl' => function ($name) {
                            return \Yii::$app->assetManager->getBundle($name)->baseUrl;
                        },
                        'organizationPath' => function($route) {
                            return \app\helpers\OrganizationUrl::to($route);
                        },
                        'staticCall' => function($class, $function, $args = array())
                        {
                            $class = str_replace('/',"\\", $class);
                            if (class_exists($class) && method_exists($class, $function)) {
                                return call_user_func_array(array($class, $function), $args);
                            }
                            return null;
                        },
                        'getDataAttribute' => function($attribute) {
                            return \Yii::$app->data->$attribute;
                        },

                        'getResourceBase' => function() {
                            return \Yii::$app->request->baseUrl;
                        },

                    ]
                ],
            ]
        ],
        'redis' => $params['redis'],
        'breadCrumbs' => [
            'class' => 'app\components\BreadCrumbs'
        ],
        'languageSetter' => [
            'class' => 'app\components\LanguageSetter'
        ],
    ],
    'params' => $params,
];

if (YII_DEBUG || $_GET['force_copy'] == 1) {
    $config['components']['assetManager']['forceCopy'] = true;
}

if (YII_ENV_DEV) {

    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.2.*']
    ];


    $config['bootstrap'] = array_merge($config['bootstrap'],
        ['log', 'debug']
    );


    // Подключаем дебаг панель
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['components']['log'] = [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@app/runtime/logs/all.log'

            ],
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['info'],
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@app/runtime/logs/trace.log',
                'logVars' => [],
                'levels' => ['trace'],
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@app/runtime/logs/error.log',
                'levels' => ['error'],
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@app/runtime/logs/db.log',
                'logVars' => [],
                'levels' => ['profile'],
                'categories' => ['yii\db\Command::query'],
                'prefix' => function ($message) {
                    return '';
                }
            ]
        ],
    ];

    // Отключаем CRSF при тестах
    $config['components']['request']['enableCsrfValidation'] = false;

    // Подключаем генератор фикстур
    Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'fixture' => [
                'class' => 'elisdn\gii\fixture\Generator',
            ],
        ],
    ];
    $config['modules']['debug'] = 'yii\debug\Module';
}

return $config;

