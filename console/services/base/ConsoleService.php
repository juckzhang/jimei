<?php
namespace console\services\base;

use backend\models\mongodb\UserModel;
use common\services\base\Service;

class ConsoleService extends Service
{
    /**
     * 后台日志
     * @param $content
     * @param null $file
     */
    protected function writeLog($content,$file = null)
    {
        if($file === null) $file = date('Y-m-d-h') . '.log';
        if(trim($content) == '') return ;

        $content = '[' . date('Y-m-d h:i:s') . '] ' . $content . PHP_EOL;
        $filePath = \Yii::getAlias('@console/runtime/logs/' . $file);
        file_put_contents($filePath,$content,FILE_APPEND);
    }
}