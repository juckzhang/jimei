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
            'dsn' => 'mysql:host=127.0.0.1;dbname=kung', // MySQL, MariaDB
            'username' => 'root',
            'password' => 'admin123',
            'charset' => 'utf8',
            'tablePrefix' => 'kung_',
        ],
    ],
    'params' => ['imageUrlPrefix' => 'zaizai'],
];
