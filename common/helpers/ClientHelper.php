<?php


namespace common\helpers;

use yii\helpers\ArrayHelper;
use common\components\client\Curl;
use yii\httpclient\Client;

class ClientHelper
{
    const APP_KEY = '200305112610017';
    // const APP_SECRET = '8348bac6ef3e4b3e8de26c83a5e75da3';
    const APP_SECRET = '32ba85dbdf7c401f9f9d5bb3f42b3cfe';
    const VERSION = '1.0';
    const URL_API = 'http://local.gjpqqd.com:5918/Service/ERPService.asmx/ERPApi';

    private static function sign($param, $data){
        $data = json_encode($data);
        $sign = \Yii::$app->params['appSecret'];//static::APP_SECRET;
        foreach ($param as $key => $value){
            $sign .= $key.$value;
        }
        $sign .= $data.\Yii::$app->params['appSecret'];//static::APP_SECRET;

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

    private static function sCurl($param, $data){
        $url = \Yii::$app->params['apiUrl'].'?'.http_build_query($param);
        $option = [
            'url' => $url,
            'method' => 'POST',
            'contentJson' => true,
            'args' => $data,
            'timeout' => 10,
        ];

        $res = Curl::sCurl($option);
        if($res) {
            $res = json_decode($res, true);
        }

        if(is_array($res)) return $res;

        return [];
    }

    //同步套餐信息
    public static function rsyncMeal($data){
        $param = static::parseParam('erp.goodssuite.sync', $data);
        $result = static::sCurl($param, $data);
        $ret = ['code' => 0, 'message' => '同步成功', 'mealCode' => []];

        if(ArrayHelper::getValue($result, 'code') === 0){
            return $ret;
        }

        $items = ArrayHelper::getValue($result, 'items', []);
        $message = [];

        foreach ($items as $item){
            $_message = ArrayHelper::getValue($item, 'message', '');
            if($_message and $_message != '商品套餐保存失败：套餐编号已经存在'){
                $message[] = $_message;
                $code = ArrayHelper::getValue($item, 'suitecode');
                if($code) $ret['mealCode'][] = $code;
            }
        }
        if(isset($result['Message'])) $message[] = $result['Message'];
        $_message = ArrayHelper::getValue($result, 'message');
        if($_message and $_message != '商品套餐信息同步') $message[] = $_message;
        if(!isset($result['code'])){
            $ret['code'] = -1;
            if(!$message) $message[] = '同步失败!';
        }
        if($message) {
            $message = array_unique($message);
            $ret['code'] = -1;
            $ret['message'] = implode('|', $message);
        }
        \Yii::$app->bizLog->log($ret, 'req', 'Info');

        return $ret;
    }

    //获取配货单列表
    public static function rsyncOrder($data){
        $param = static::parseParam('erp.dist.get', $data);
        $result = static::sCurl($param, $data);

        if(ArrayHelper::getValue($result, 'code') == 0){
            return $result;
        }

        return [];
    }

    /**
     * 获取预扣款订单列表
     * pageno	当前页数	Y	int	从1开始
     * pagesize	每页数据条数	Y	int	最大值100
     * starttime	制单时间开始	Y	datatime	
     * endtime	制单时间结束	Y	datatime	
     * orderstatus	单据状态	Y	string(25)	audit=审核、financeaudit=财审、send=发货
     */
    public static function orderList($data){
        $param = static::parseParam('erp.jqmorder.get', $data);
        $result = static::sCurl($param, $data);

        if(ArrayHelper::getValue($result, 'code') == 0){
            return $result;
        }

        return [];
    }

    /**
     * 财审接口
     * billNoList	全渠道订单ID集合	Y	array
     */
    public static function FinanceAuth($data)
    {
        $param = static::parseParam('erp.order.financeaudit', $data);
        $result = static::sCurl($param, $data);

        if(ArrayHelper::getValue($result, 'code') == 0){
            return $result;
        }

        return [];
    }
}