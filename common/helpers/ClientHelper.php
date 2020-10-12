<?php


namespace common\helpers;

use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class ClientHelper
{
    const APP_KEY = '200305112610017';
    const APP_SECRET = '38ad607f57244f42ae303bfbf9b86463';
    const VERSION = '1.0';
    const URL_API = 'http://local.gjpqqd.com:5918/Service/ERPService.asmx/ERPApi';

    private static function sign($param, $data){
        $data = json_encode($data);
        $sign = static::APP_SECRET;
        foreach ($param as $key => $value){
            $sign .= $key.$value;
        }
        $sign .= $data.static::APP_SECRET;

        return strtoupper(md5($sign));
    }

    private static function parseParam($method, $data){
        $param = [
            'method' => $method,
            'v' => static::VERSION,
            'format' => 'json',
            'app_key' => static::APP_KEY,
            'timestamp' => date('Y-m-d H:i:s'),
            'sign_method' => 'md5',
        ];
        ksort($param);
        $param['sign'] = static::sign($param, $data);

        return $param;
    }

    private function sCurl($param, $data){
        $url = static::URL_API.'?'.http_build_query($param);
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