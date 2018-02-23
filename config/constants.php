<?php
return [
    'secret_word' => 'mysecretword',
    'socket_server' => 'http://127.0.0.1:8888',
    'api_url' => 'http://krw-api/',
    'host' => 'http://krw/',
    'forum_host' => 'http://krw/forum',
    'files_host' => "https://ff.bilimal.kz/upload",
    'db' => [
        'host' => 'db.verter.vpn',
        'port' => 5432,
        'name' => 'db_krw_new',
        'name_test' => 'krw_test',
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
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.mail.ru',
        'username' => 'noreply@bilimal.kz',
        'password' => 'CWEqcd93',
        'port' => '587',
        'encryption' => 'tls',
    ],
    'rabbit' => [
        'host' => 'rabbitmq.vpn',
        'port' => 5672,
        'user' => 'bilimal',
        'password' => 'bilimal',
    ],
    'redis' => [
        'class' => 'yii\redis\Connection'
    ],
//    'bigbluebutton' => [
//        'url' => 'https://bbb.bilimal.kz/bigbluebutton/',
//        'secret' => '4ccf389ba8a8b0edce492f94f7ad9903'
//    ]
    'bigbluebutton' => [
        'url' => 'https://bbb.sdot.bilimal.kz/bigbluebutton/',
        'secret' => '88b293eab060d638764b6f892f767686'
    ]
];