<?php

$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

return [
    'class' => common\components\Connection::class,
    'dsn' => 'pgsql:host=' . $params['db_bilimal_old']['host'] . ';port=5432;dbname=' . $params['db_bilimal_old']['name'],
    'username' => $params['db_bilimal_old']['user'],
    'password' => $params['db_bilimal_old']['password'],
    'charset' => 'utf8',
];
