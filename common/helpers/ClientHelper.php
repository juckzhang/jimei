<?php


namespace common\helpers;

use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class ClientHelper
{
    const APP_KEY = '200305112610017';
    const APP_SECRET = '8348bac6ef3e4b3e8de26c83a5e75da3';
    const VERSION = '1.0';
    const URL_API = 'http://local.gjpqqd.com:5918/Service/ERPService.asmx/ERPApi';
    const LOG_FILE = '/mnt/data/openresty/htdocs/jimei/backend/runtime/logs/tt.log';

    private static function sign($param, $data){
        $data = json_encode($data);
        $sign = \Yii::$app->params['appSecret'];//static::APP_SECRET;
        foreach ($param as $key => $value){
            $sign .= $key.$value;
        }
        $sign .= $data.\Yii::$app->params['appSecret'];//static::APP_SECRET;

        file_put_contents(static::LOG_FILE, $sign.PHP_EOL.PHP_EOL);
        return strtoupper(md5($sign));
    }

    private static function parseParam($method, $data){
        $param = [
            'method' => $method,
            'v' => static::VERSION,
            'format' => 'json',
            'app_key' => \Yii::$app->params['appKey'],//static::APP_KEY,
            'timestamp' => date('Y-m-d H:i:s'),
            'sign_method' => 'md5',
        ];
        ksort($param);
        $param['sign'] = static::sign($param, $data);

        return $param;
    }

    private function sCurl($param, $data){
        $url = \Yii::$app->params['apiUrl'].'?'.http_build_query($param);
        $response = (new Client(
            [
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON,
                ],
            ]
        ))->createRequest()
            ->setMethod('post')
            ->setData($data)
            ->setUrl($url)
            ->setFormat(Client::FORMAT_JSON)
            ->setHeaders(['Content-Type'=>'application/json'])
            ->send();

        $msg = $url.PHP_EOL.PHP_EOL.json_encode($data).PHP_EOL.PHP_EOL.json_encode($response->data).PHP_EOL;
        file_put_contents(static::LOG_FILE, $msg, FILE_APPEND);
        if($response->isOk) return $response->data;

        return [];
    }

    public static function rsyncMeal($data){
        $param = static::parseParam('erp.goodssuite.sync', $data);
        $result = static::sCurl($param, $data);

        if(ArrayHelper::getValue($result, 'code') == 0){
            return ArrayHelper::getValue($result, 'items');
        }

        return false;
    }

    public static function rsyncOrder($data){
        $param = static::parseParam('erp.dist.get', $data);
        $result = static::sCurl($param, $data);

        if(ArrayHelper::getValue($result, 'code') == 0){
            return $result;
        }

        return [];
    }
}