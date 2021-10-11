<?php
return [
    'id' => 'yii-console',
    'basePath' => dirname(__DIR__ ),
    'controllerNamespace' => 'console\controllers',
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error', 'warning'],
                    'message' => [
                        'from' => ['log@example.com'],
                        'to' => ['developer1@example.com', 'developer2@example.com'],
                        'subject' => 'Log message',
                    ],

                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=jimei', // MySQL, MariaDB
            'username' => 'root',
            'password' => 'ZCP38N8H.Yjt',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'jimei_',
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
                        'tags' => ['req', 'curl', 'mysql'], //配置需要记录的tag
                        'levels' => YII_DEBUG ? ['Error', 'Debug', 'Info'] : ['Error', 'Info'], //关注的日志等级
                    ],
                ],
            ],
        ],
    ],
    'params' => [
        'imageUrlPrefix' => 'zaizai',
        'appKey'       => '200428181312266',
        'appSecret'    => 'c8eb893fdf764709a863bab7e8cbf4fb',
        'apiUrl'       => 'https://openapi.gjpqqd.com/Service/ERPService.asmx/ERPApi',
        // 'appKey'       => '200305112610017',
        // 'appSecret'    => '32ba85dbdf7c401f9f9d5bb3f42b3cfe',
        // 'apiUrl'       => 'http://local.gjpqqd.com:5918/Service/ERPService.asmx/ERPApi',
    ],
];
