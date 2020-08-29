<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'debug' => 'yii\debug\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'jimei_',
        ],

        'config' => [
            'class' => 'common\components\Config',
            'ConfigPaths' => ['@common'],
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