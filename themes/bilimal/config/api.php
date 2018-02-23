<?php

$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

$config = require $_SERVER['DOCUMENT_ROOT']."/protected/config/api.php";


$config['aliases']['@bilimal'] = dirname(__DIR__) . '/../bilimal';
$config['components']['db'] = require __DIR__ . '/db.php';
$config['components']['db_bilimal'] = require __DIR__ . '/db_bilimal.php';
$config['components']['db_bilimal_old'] = require __DIR__ . '/db_bilimal_old.php';
$config['params'] = $params;

$config['controllerMap'] = [
    'assign' => 'bilimal\api\controllers\AssignController'
];

$config['components']['user']['identityClass'] = 'bilimal\api\models\Users';

$config['bootstrap'][] = function() {

    Yii::$container->setDefinitions([
        'common\models\Organizations' => [
            'class' => 'bilimal\web\models\Organizations'
        ],
        'common\models\relations\UserOrganization' => [
            'class' => 'bilimal\common\models\relations\UserOrganization'
        ],
        'common\models\relations\EventOrganization' => [
            'class' => 'bilimal\common\models\relations\EventOrganization'
        ],
    ]);

};

return $config;

