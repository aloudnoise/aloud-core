<?php
$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

$config = require $_SERVER['DOCUMENT_ROOT']."/protected/config/web.php";
$config['aliases']['@bilimal'] = dirname(__DIR__) . '/../bilimal';
$config['components']['db'] = require __DIR__ . '/db.php';
$config['components']['db_bilimal'] = require __DIR__ . '/db_bilimal.php';
$config['components']['db_bilimal_old'] = require __DIR__ . '/db_bilimal_old.php';
$config['params'] = $params;

$config['controllerMap'] = [
    'auth' => 'bilimal\web\controllers\AuthController',
    'organizations' => 'bilimal\web\controllers\OrganizationsController'
];

$config['modules']['hr']['controllerMap'] = [
    'groups' => 'bilimal\web\controllers\GroupsController'
];

$config['modules']['cabinet']['controllerMap'] = [
    'base' => 'bilimal\web\modules\cabinet\controllers\BaseController'
];

$config['components']['user']['identityClass'] = 'bilimal\web\models\Users';
$config['components']['user']['identityCookie'] = ['name' => '_bilimal_identity', 'httpOnly' => true];

$config['components']['session'] = [
    'class' => 'yii\web\Session',
    'name' => 'LMSSESSION'
];

$config['components']['cache']['keyPrefix'] = 'lms_cache:';

$config['components']['view']['theme']['pathMap']['@app/views'] = '@bilimal/web/views';
$config['components']['view']['theme']['pathMap']['@app/widgets/EProfile/views'] = '@bilimal/web/widgets/EProfile/views';
$config['bootstrap'][] = function() {

    Yii::$container->setDefinitions([
        'common\models\Organizations' => [
            'class' => 'bilimal\web\models\Organizations'
        ],
        'app\models\reports\ReportsFilter' => [
            'class' => 'bilimal\web\models\reports\ReportsFilter'
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

