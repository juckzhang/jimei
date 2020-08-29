<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=kung', // MySQL, MariaDB
            'username' => 'root',
            'password' => 'kung_admin_123@&',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'kung_',
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