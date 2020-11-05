<?php
return [
    'id' => 'yii-console',
    'basePath' => dirname(__DIR__ ),
    'controllerNamespace' => 'console\controllers',
    'bootstrap' => ['log'],
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
    ],
    'params' => [
        'imageUrlPrefix' => 'zaizai',
        'appKey'       => '200428181312266',
        'appSecret'    => '81edd4791db141b386f066ffda128a38',
        'apiUrl'       => 'https://openapi.gjpqqd.com/Service/ERPService.asmx/ERPApi',
    ],
];
