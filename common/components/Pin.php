<?php
namespace common\components;

use Overtrue\Pinyin\Pinyin;
use yii\base\Component;

class Pin extends Component
{
    private $_pin;

    public function init()
    {
        $this->_pin = new Pinyin();
    }

    // 读音
    public function __call($name, $params)
    {
        return call_user_func_array([$this->_pin, $name], $params);
    }
}