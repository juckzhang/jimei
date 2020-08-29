<?php
namespace common\helpers;
use \yii\base\InvalidConfigException;
use \yii\base\InvalidParamException;
use \yii\helpers\ArrayHelper;

class CommonHelper {

    public static function loadConfig($file,$configPath = [])
    {
        $_configPaths = ['@common', '@backend'];
        if( ! empty($configPath)) $_configPaths = $configPath;
        $_config = [];

        $file = ($file === '') ? 'main' : str_replace('.php', '', $file);

        foreach ($_configPaths as $_path)
        {
            foreach ([$file, YII_ENV .'/'.$file] as $location)
            {
                $_filePath = \yii::getAlias($_path.'/config/'.$location.'.php');

                if ( ! file_exists($_filePath))
                {
                    continue;
                }

                $_configSection = include ($_filePath);

                if ( ! is_array($_configSection))
                    throw new InvalidConfigException('Your '.$_filePath.' file does not appear to contain a valid configuration array.');

                $_config = ArrayHelper::merge($_config, $_configSection);
            }
        }

        return $_config;
    }

    public static function randString($length = 6,$source = '')
    {
        $source = is_string($source) && $source !== '' ?
            $source : 'QWERTYUIPASDFGHJKLZXCVBNM123456789qwertyuipasdfghjklzxcvbnm';

        $_strLength = strlen($source);

        if($length > $_strLength)
            throw new InvalidParamException('Param error for $length too long ' . $length);

        $_str = '';
        for($i = 0; $i <$length; ++$i )
        {
            srand(time());
            $_random = mt_rand(0,$_strLength - 1);
            $_str .= $source[$_random];
        }

        return $_str;
    }

    public static function t($category, $message){
        $lang = \Yii::$app->request->getPost('lang', 'zh_CN');
        \Yii::$app->lang->load($category, $lang);
        $message = \Yii::$app->lang->line($message);

        return $message;
    }
}