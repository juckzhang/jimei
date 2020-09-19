<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=jimei', // MySQL, MariaDB
            'username' => 'root',
            'password' => 'ZCP38N8H.Yjt',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'jimei_',
        ],

        'uploadTool' => [
            'class' => 'common\components\uploadRemote\UploadTool',
            'handler' => [
                'class' => 'common\components\uploadRemote\UploadAliYun',
                'accessKeyId' => 'LTAIhqAEiHvZxEs3',
                'accessKeySecret' => 'HZnqx1EnrjLv4WZCUNNOoqx4NjHRkS',
                'bucket' => 'kongchinese1',
                'endPoint' => 'oss-cn-hongkong.aliyuncs.com',
            ],
        ],
    ],
];