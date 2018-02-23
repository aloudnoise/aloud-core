<?php
return
    [
        ['class' => 'api\components\UrlRule', 'controller' => 'users/auth', 'pluralize'=>false,
            'except' => ['index','view','update']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'users/registration', 'pluralize'=>false,
            'except' => ['index','view','update','delete']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'dic', 'pluralize'=>false,
            'extraPatterns' => [
                'GET {id}' => 'view',
            ],
            'except' => ['create','update','delete']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'assign', 'pluralize'=>false,
            'except' => ['index','view','update','delete']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'tests/questions', 'pluralize'=>false,
            'except' => ['view', 'create', 'update','delete']
        ],
        ['class' => 'api\components\UrlRule', 'controller' => 'tests/import', 'pluralize'=>false,
            'except' => ['view', 'index', 'update','delete']
        ],
        ['class' => 'api\components\UrlRule', 'controller' => 'tests/process', 'pluralize'=>false,
            'except' => ['view', 'index', 'create','delete']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'tasks/check', 'pluralize'=>false,
            'except' => ['view', 'index', 'create', 'delete']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'library/process', 'pluralize'=>false,
            'except' => ['view', 'index', 'create','delete']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'export', 'pluralize'=>false,
            'except' => ['view', 'index', 'update','delete']
        ],

        ['class' => 'api\components\UrlRule', 'controller' => 'bbb', 'pluralize'=>false,
            'except' => ['view', 'index', 'update','delete']
        ],

    ];