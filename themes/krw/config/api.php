<?php
$config = require $_SERVER['DOCUMENT_ROOT']."/protected/config/api.php";
$config['aliases']['@krw'] = dirname(__DIR__) . '/../krw';
return $config;

