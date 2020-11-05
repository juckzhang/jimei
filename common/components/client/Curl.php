<?php
namespace common\component\client;

class Curl extends Handle{
    //单请求
    public static function sCurl($option) {
        $chObj          = new self($option);
        $ch             = $chObj->get();
        $starttime      = microtime(true);
        $result         = curl_exec($ch);
        $endtime        = microtime(true);

        //记录日志信息
        $logInfo = [
            'type' => 'sCurl',
            'option' => $option,
            'time' => sprintf("%.3f" ,($endtime - $starttime)),
            'errMessage' => '',
            'result' => $result,
        ];

        if (curl_errno($ch)) {
            $result     = false;
            $err        = curl_error($ch);
            $logInfo['errMessage'] = $err;
//            Log::error($logInfo, 'task-collector');
        }else{
//            Log::debug($logInfo, 'task-collector');
        }

        curl_close($ch);

        return $result;
    }

    /**
     * 优化后的multi_curl，经过ab工具测试分析
     *
     * @param array $options
     * @return array
     */
    public static function mCurl($options) {
        $start          = microtime(true);
        $result         = array();
        $map            = array();
        $errorMsg       = array();

        $mch            = curl_multi_init();
        foreach ($options as $i => $opt) {
            $chObj      = new self($opt);
            $ch         = $chObj->get();
            curl_multi_add_handle ($mch, $ch);
            $map[$i]    = $ch;
        }
        # execute the handles
        $active = null;
        do {
            $mrc = curl_multi_exec($mch, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ( $active && $mrc == CURLM_OK ) {
            if (curl_multi_select($mch, 0.8) == - 1) {      # select超时返回0,失败返回-1,当超时的时候setOpt中的timeout就起作用了,使用man select 查看实现原理
                usleep(100);
            }
            do {
                $mrc = curl_multi_exec($mch, $active);
            } while ( $mrc == CURLM_CALL_MULTI_PERFORM );
        }
        # get results
        foreach ($map as $k => $handle) {
            if (curl_error($handle) == '') {
                $temp   = curl_multi_getcontent($handle);
                $count  = 0;
                while (!$temp) {
                    if ($count > 3)
                        break;
                    usleep (100);
                    $temp = curl_multi_getcontent ($handle);
                    $count ++;
                }
                $result[$k] = $temp;
                curl_multi_remove_handle($mch, $handle);
                curl_close ($handle);
            } else {
                $errorMsg[$options[$k]['url']] = curl_error($handle);
            }
        }
        curl_multi_close($mch);

        if(!empty($errorMsg)){
            $errInfo            = empty($errorMsg) ? '' : serialize($errorMsg);
            $loginfo            = count($options) . '|' . sprintf("%.4f", microtime(true) - $start) . '|' . $errInfo;
//            Log::error('mCurl:'.$loginfo, 'task-collector');
        }else{
//            Log::debug(['options' => $options, 'result' => $result]);
        }

        return $result;
    }

}
