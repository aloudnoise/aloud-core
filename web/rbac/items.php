<?php
return [
    'guest' => [
        'type' => 1,
    ],
    'SUPER' => [
        'type' => 1,
        'children' => [
            'admin',
        ],
    ],
    'base_teacher' => [
        'type' => 1,
        'children' => [
            'pupil',
        ],
    ],
    'specialist' => [
        'type' => 1,
        'children' => [
            'teacher',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'specialist',
        ],
    ],
    'teacher' => [
        'type' => 1,
        'children' => [
            'base_teacher',
        ],
    ],
    'base_pupil' => [
        'type' => 1,
    ],
    'pupil' => [
        'type' => 1,
        'children' => [
            'base_pupil',
        ],
    ],
];
