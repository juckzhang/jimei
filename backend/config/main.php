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
            'enableAutoLogin' => false,
            'idParam' => 'zuiying_backend_id',
            'identityCookie' => ['name' => 'zuiying_backend_identity', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
            'on afterLogin' => function($event){
                $user = $event->identity;
                if($user) $user->updateAuthKey();
            },
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

        //日志
        'bizLog' => [//日志组件
            'class' => 'common\components\log\Logger',
            'dispatcher' => [
                'targets' => [
                    'file' => [
                        'class' => 'common\components\log\FileTarget',
                        'filePath' => '@runtime/logs',
                        'commonPrefix' => 'jimei',
                        'tags' => ['req', 'curl',], //配置需要记录的tag
                        'levels' => YII_DEBUG ? ['Error', 'Debug', 'Info'] : ['Error', 'Info'], //关注的日志等级
                    ],
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