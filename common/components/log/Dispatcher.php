<?php
namespace common\components\log;

use yii\base\Component;

class Dispatcher extends Component {
    public $targets;

    public function init(){
        foreach($this->targets as $name  => $config){
            $this->targets[$name] = \Yii::createObject($config);
        }
    }

    public function dispatch(array $messages)
    {
        foreach ($this->targets as $target) {
            if($target->enable)
                $target->collect($messages);
        }
    }
}