<?php
namespace common\components\log;

use yii\base\Component;

class Logger extends Component {
    const LOG_LEVEL_INFO = 'Info';
    const LOG_LEVEL_ERROR = 'Error';
    const LOG_LEVEL_WARING = 'Waring';
    const LOG_LEVEL_NOTICE = 'Notice';
    const LOG_LEVEL_DEBUG = 'Debug';
    const LOG_LEVEL_TRACE = 'Trace';

    private $_messages = [];
    private $_startTime = 0;
    private $seqId;

    public  $dispatcher;
    public  $limit = 1;
    public  $waitTime = 5;

    public function init(){
        if( !isset($this->dispatcher['class']))
            $this->dispatcher['class'] = 'common\components\log\Dispatcher';

        $this->dispatcher = \Yii::createObject($this->dispatcher);

        register_shutdown_function(function () {
            $this->flush();
            register_shutdown_function([$this, 'flush']);
        });

        $this->seqId = sprintf("%d-%d", time(), mt_rand(1000000, 999999));
    }

    public function updateSeqId(){
        $this->seqId = sprintf("%d-%d", time(), mt_rand(1000000, 999999));
    }

    public function log($message, $tag, $level)
    {
        $timestamp = time();

        if(empty($this->_messages)){
            $this->_startTime = $timestamp;
        }
        $this->_messages[] = [
            'message' => $message,
            'level' => $level,
            'tag' => $tag,
            'seqId' => $this->seqId,
            'timestamp' => $timestamp,
        ];
        //判断日志条数是否大于指定数量
        if(count($this->_messages) >= $this->limit || ($timestamp - $this->_startTime) >= $this->waitTime){
            $this->flush();
        }
    }

    public function flush()
    {
        if( ! ($this->dispatcher instanceof Dispatcher) )
            throw new \Exception('log dispatcher is not find！');

        if(!empty($this->_messages))
            $this->dispatcher->dispatch($this->_messages);

        $this->_messages = [];
    }
}
