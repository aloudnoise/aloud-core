<?php
return [
    'jsCompressor' => 'java -jar compiler.jar --js {from} --js_output_file {to}',
    'cssCompressor' => 'java -jar yuicompressor.jar --type css {from} -o {to}',
    'deleteSource' => false,
    'bundles' => [
        'aloud_core\web\bundles\base\BaseBundle',
        'aloud_core\web\bundles\jquery\JQueryBundle',
        'aloud_core\web\bundles\bootstrap\BootstrapBundle',
        'aloud_core\web\bundles\urlmanager\UrlManagerBundle',
        'aloud_core\web\bundles\backbone\BackboneBundle',
        'aloud_core\web\bundles\jstrans\JSTransBundle',
        'aloud_core\web\bundles\font_awesome\FontAwesomeBundle',
    ],
    'targets' => [
        'all' => [
            'class' => 'aloud_core\web\bundles\base\BaseBundle',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
        ],
    ],
    'assetManager' => [
    ],
];