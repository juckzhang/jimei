<?php
return [
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'request' => [
            'class' => 'common\components\Request',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
];