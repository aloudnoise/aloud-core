<?php

require __DIR__ . '/constants.php';
$dbParams = require __DIR__ . '/test_db.php';

// Override base config for testing purposes
$config = require __DIR__ . '/web.php';
$config['id'] = 'basic-tests';
$config['components']['db'] = $dbParams;
$config['components']['request']['enableCsrfValidation'] = false;

return $config;