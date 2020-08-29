<?php

namespace common\components;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\constants\CodeConstant;
use common\helpers\CommonHelper;

/**
 * Class RpcClient
 * @package common\components
 */
class RpcClient extends Component {

    /**
     * @param $class
     * @param $method
     * @param array $args
     * @param string $group
     * @param int $timeout
     * @return mixed
     * @throws InvalidConfigException
     * @throws \exception
     */
    public function getRemoteService($class, $method, $args = array(), $group = 'default', $timeout = 50) {

        // load config
        $rpcConfig = CommonHelper::loadConfig('rpc');

        //获取客户端配置信息
        $customer = ArrayHelper::getValue($rpcConfig,'customer');

        if (!isset($customer) || !isset($customer[$group])) {
            throw new \exception("Please check your config['customer']!", 405);
        }

        $index = rand(0, (count($customer[$group]['baseUrl']) - 1));
        $url = $customer[$group]['baseUrl'][$index];
        //$file = $customer[$group]['file'];
        //$url = 'http://' . $host . '/' . ltrim($file, '/');

        $temp = array(
            'class' => $class,
            'method' => $method,
            'args' => $args,
        );

        if($customer[$group]['type']) {
            $temp['openId'] = $customer[$group]['openId'];
            $sign = md5(md5($temp['class'] . $temp['method'] . Json::encode($temp['args'])) . $customer[$group]['secretKey']);
            $temp['s'] = $sign;
        }

        try {
//            $_return = $this->_post($url, $temp, NULL, $timeout);
            $_return = $this->_createRequest($url, $temp, NULL, $timeout);
//            $_return = json_decode($_return, TRUE);

        } catch (\Exception $e) {
            $_return['s'] = CodeConstant::RPC_FAILED;
        }
        return $_return;
    }

    /**
     * 创建请求
     * @param $url
     * @param $data
     * @param null $proxy
     * @param int $timeout
     * @param array $header
     * @return bool
     */
    private function _createRequest($url, $data, $proxy = NULL, $timeout = 10, $header = [])
    {
        if( !$url) return false;
        $client = new Client([
            //'url' => $url,
            'responseConfig' => [
                'format' => Client::FORMAT_JSON,
            ],
        ]);
        $response = $client->createRequest()
            ->setMethod('post')
            ->addOptions(['timeout' => $timeout])
            ->setUrl($url)
            ->setHeaders($header)
            ->setData($data);

        //不验证https证书
        if (stripos($url, 'https://') === 0)
            $response = $response->addOptions(['sslVerifyPeer' => false]);

        $response = $response->send();
        if ($response->isOk) {
            return $response->data;
        }
        return false;
    }

    /**
     * @param $url
     * @param $data
     * @param null $proxy
     * @param int $timeout
     * @param null $header
     * @return bool|mixed
     * @deprecate 该方法已由 _createRequest方法代替
     * @throws \Exception
     */
    private function _post($url, $data, $proxy = NULL, $timeout = 10, $header = NULL) {

        if (!$url) return FALSE;
        if ($data) {
            $data = http_build_query($data);
        }

        if (stripos($url, 'https://') === 0) {
            $ssl = TRUE;
        } else {
            $ssl = FALSE;
        }

        var_dump($url."?$data");exit;
        $curl = curl_init();

        if (!is_null($proxy)) curl_setopt($curl, CURLOPT_PROXY, $proxy);
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        }
//        curl_setopt($curl,CURLOPT_PORT,88);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        if (is_array($header))
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $content = curl_exec($curl);
        var_dump($content);exit;
        $curl_errno = curl_errno($curl);
        if ($curl_errno > 0) {
            $error = sprintf('curl error=%s, errno=%d.', curl_error($curl), $curl_errno);
            curl_close($curl);
            throw new \Exception($error);
        }
        curl_close($curl);
        return $content;
    }
}