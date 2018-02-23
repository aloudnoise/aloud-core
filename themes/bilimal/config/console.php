<?php

$params = array_merge(
    require __DIR__ . '/constants.php',
    require __DIR__ . '/params.php'
);

$config = require dirname(__DIR__)."/../../config/console.php";
$config['aliases']['@bilimal'] = dirname(__DIR__) . '/../bilimal';
$config['components']['db'] = require __DIR__ . '/db.php';
$config['components']['db_bilimal'] = require __DIR__ . '/db_bilimal.php';
$config['components']['db_bilimal_old'] = require __DIR__ . '/db_bilimal_old.php';
$config['params'] = $params;

return $config;

