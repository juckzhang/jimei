<?php
namespace common\components;

use Google\Cloud\Translate\TranslateClient;
use yii\base\Component;

class Translation extends Component
{
    public $key;
    private $_client;
    private $_lang = [
        'en' => 'en_US',
        'zh-CN' => 'zh_CN',
    ];

    public function init()
    {
        if(!($this->_client instanceof TranslateClient)){
            $this->_client = new TranslateClient(['key' => $this->key]);
        }
    }

    public function lang($lang, $flip = false){
        $_lang = $flip ? array_flip($this->_lang) : $this->_lang;

        return isset($_lang[$lang]) ? $_lang[$lang] : $lang;
    }

    public function translate($text, $target)
    {
        return $this->_client->translate($text, [
            'target' => $target
        ]);
    }

    public function __call($name, $params)
    {
        return call_user_func_array([$this->_client, $name], $params);
    }
}