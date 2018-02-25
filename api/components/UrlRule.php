<?php

namespace aloud_core\api\components;

class UrlRule extends \yii\rest\UrlRule
{

    public $tokens = [
        '{id}' => '<id:.*>',
    ];

    public $patterns = [
        'PUT,PATCH {id}' => 'update',
        'DELETE {id}' => 'delete',
        'DELETE' => 'delete',
        'GET info' => 'info',
        'GET count' => 'count',
        'GET,HEAD {id}' => 'view',
        'POST' => 'create',
        'GET,HEAD' => 'index',
        '{id}' => 'options',
        '' => 'options',
    ];

}

?>