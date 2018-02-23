<?php

$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

return [
    'class' => common\components\Connection::class,
    'dsn' => 'pgsql:host=' . $params['db']['host'] . ';port=5432;dbname=' . $params['db']['name'],
    'username' => $params['db']['user'],
    'password' => $params['db']['password'],
    'charset' => 'utf8',
    'enableSchemaCache' => true,
];
