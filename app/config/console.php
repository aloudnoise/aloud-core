<?php

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . '/../vendor',
    'components' => [
        '@aloud_core' => dirname(__DIR__) . '/..',
    ]
];


return $config;
