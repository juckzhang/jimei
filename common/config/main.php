<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'debug' => 'yii\debug\Module',
    ],
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'jimei_',
        ],

        'config' => [
            'class' => 'common\components\Config',
            'ConfigPaths' => ['@common'],
        ],
        'lang' => [
            'class' => 'common\components\Lang',
        ],

        'uploadTool' => [
            'class' => 'common\components\uploadRemote\UploadTool',
        ],

        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset'=>[
                    'js' => []
                ]
            ]
        ],
    ],
    'params' => \common\helpers\CommonHelper::loadConfig('params',['@common']),
];