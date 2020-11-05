<?php
namespace common\components\client;
abstract class Handle {
    const CURL_BASE_VERSION    = 0x71B00;

    private $ch                = null;

    public function __construct($option) {
        $this->ch = curl_init();
        $this->setOpt($option);
    }

    public function get() {
        return $this->ch;
    }

    private function setOpt($option) {
        $method = isset($option['method']) ? strtoupper($option['method']) : 'GET';
        if ($method != 'POST' && isset($option['args']))
            foreach($option['args'] as $key => $value) {
                $option['url'] .= strpos($option['url'], '?') ? '&'.$key.'='.rawurlencode($value) : '?'.$key.'='.rawurlencode($value);
            }
    
        curl_setopt($this->ch, CURLOPT_URL, $option['url']);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $ctimeout = isset($option['ctimeout']) ? $option['ctimeout'] : 1;
        $timeout  = isset($option['timeout']) ? $option['timeout'] : 1;
        $version  = curl_version();
        if ($version['version_number'] >= self::CURL_BASE_VERSION){
            curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT_MS, $ctimeout * 1000);
            curl_setopt($this->ch, CURLOPT_NOSIGNAL, 1); 
            curl_setopt($this->ch, CURLOPT_TIMEOUT_MS, $timeout * 1000);
        } else {
            $ctimeout    = 1;
            $timeout     = ($timeout >= 1)? intval($timeout) : 1;
            curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $ctimeout);
            curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);    //执行超时
        }
        switch ($method) {
        case 'POST' :
            curl_setopt($this->ch, CURLOPT_POST, true);
            if (isset($option['args']) && $option['args']) {
                if(isset($option['contentJson'])){
                    if(!isset($option['header']) or !is_array($option['header'])) $option['header'] = [];
                    $option['header'][] = 'Content-Type: application/json';
                    $postData = json_encode($option['args']);
                    $option['header'][] = 'Content-Length: '.strlen($postData);

                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);
                }else
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($option['args']));
            }
            break;
        case 'DELETE' :
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
        }
        if (isset($option['cookie']))
            curl_setopt($this->ch, CURLOPT_COOKIE, $option['cookie']);
    
        if (isset($option['header']))
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $option['header']);
    }
}
