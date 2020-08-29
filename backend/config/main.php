<?php
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh_CN',

    //百度编辑器
    'controllerMap' => [
        'ueditor' => [
            'class' => 'backend\controllers\EditorController',
        ]
    ],
    'components' => [
        'user' => [
            'class'    => '\yii\web\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'idParam' => 'zuiying_backend_id',
            'identityCookie' => ['name' => 'zuiying_backend_identity', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'authManager' => [
            'class' => 'common\components\rabc\DbManager',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

        'request' => [
            'class' => 'common\components\Request',
            'cookieValidationKey' => '1234567890qwertyuioasdfgh',
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'config' => [
            'ConfigPaths' => ['@backend'],
        ],
    ],
    'params' => \common\helpers\CommonHelper::loadConfig('params',['@backend']),
];