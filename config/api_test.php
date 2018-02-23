<?php
// Параметры для тестирования АПИ
// api-test.codo.local

require __DIR__ . '/constants.php';
$dbParams = require __DIR__ . '/test_db.php';

// Override base config for testing purposes
$config = require __DIR__ . '/api.php';
$config['id'] = 'basic-tests';
$config['components']['db'] = $dbParams;
$config['components']['request']['enableCsrfValidation'] = false;

return $config;
