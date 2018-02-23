<?php

$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

return [
    'class' => common\components\Connection::class,
    'dsn' => 'pgsql:host=' . $params['db_bilimal']['host'] . ';port=5432;dbname=' . $params['db_bilimal']['name'],
    'username' => $params['db_bilimal']['user'],
    'password' => $params['db_bilimal']['password'],
    'charset' => 'utf8',
];
