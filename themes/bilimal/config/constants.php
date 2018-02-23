<?php
return [
    'secret_word' => 'mysecretword',
    'socket_server' => 'http://127.0.0.1:8888',
    'host' => 'http://lms/',
    'files_host' => "https://ff.bilimal.kz/upload",
    'api_url' => 'http://lms-api/',
    'db' => [
        'host' => 'db.verter.vpn',
        'port' => 5432,
        'name' => 'lms',
        'name_test' => 'lms_test',
        'user' => 'postgres',
        'password' => 'postgres',
    ],
    'db_bilimal' => [
        'host' => 'pg1.vpn',
        'port' => 5432,
        'name' => 'db_bilimal_v_2',
        'user' => 'bilimal',
        'password' => 'p97356y20734f',
    ],
    'db_bilimal_old' => [
        'host' => 'database.vpn',
        'port' => 5432,
        'name' => 'db_bilimal',
        'user' => 'postgres',
        'password' => 'postgres',
    ],
    'tincan' => [
        'endpoint' => 'https://lrs.bilimal.kz/data/xAPI',
        'version' => '1.0.0',
        'username' => 'dc1b6568c0340afecf265f381abcd84092553f9c',
        'password' => '337c3891e1c4772716c55136a37bd5c612dfb944'
    ],
    'fcm_global_api' => '',
    'mailer' => [
        'transport' => 'smtp',
        'host' => 'smtp.mail.ru',
        'port' => 587,
        'username' => 'noreply@bilimal.kz',
        'password' => 'CWEqcd93',
        'encryption' => 'tls'
    ],
    'redis_prefix' => 'krw_redis:',
    'redis' => [
        'class' => 'yii\redis\Connection'
    ],
    'rabbit' => [
        'host' => 'rabbitmq.vpn',
        'port' => 5672,
        'user' => 'bilimal',
        'password' => 'bilimal',
    ],
    'bigbluebutton' => [
        'url' => 'https://bbb.bilimal.kz/bigbluebutton/',
        'secret' => '4ccf389ba8a8b0edce492f94f7ad9903'
    ]
];