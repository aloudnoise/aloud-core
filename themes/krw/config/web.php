<?php
$config = require $_SERVER['DOCUMENT_ROOT']."/protected/config/web.php";
$config['aliases']['@krw'] = dirname(__DIR__);
$config['components']['assetManager']['bundles']['bootstrap'] = [
    'class' => 'krw\web\bundles\bootstrap\BootstrapBundle',
];

$config['components']['assetManager']['bundles']['theme'] = [
    'class' => 'krw\web\bundles\theme\ThemeBundle',
];

$config['components']['session'] = [
    'class' => 'yii\web\Session',
    'name' => 'KRWSESSION'
];

$config['components']['view']['theme']['pathMap']['@app/modules/hr/views/users'] = '@krw/web/modules/hr/views/users';
//$config['components']['view']['theme']['pathMap']['@app/views/layouts'] = '@krw/web/views/layouts';
$config['bootstrap'][] = function() {

    Yii::$container->setDefinitions([
        'app\models\reports\ReportsFilter' => [
            'class' => 'krw\web\models\reports\ReportsFilter'
        ]
    ]);

};

return $config;

