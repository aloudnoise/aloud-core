<?php
$db = require __DIR__ . '/db.php';
$db['dsn'] = 'pgsql:host=' . $params['db']['host'] . ';port=5432;dbname='.$params['db']['name_test'];

return $db;