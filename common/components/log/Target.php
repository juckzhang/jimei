<?php
namespace common\components\log;
use yii\base\Component;
use yii\helpers\ArrayHelper;

abstract class Target extends Component {
    public $tags = [];

    public $levels = ['Error', 'Info', 'Warning', 'Trace', 'Debug'];

    public $enable = true;

    //表示一分钟内超过10次发送失败则报警
    public $errConf = [
        'times' => 10,//指定timeSpan时间间隔内发送失败的次数上限。超过此上限发送报警
        'timeSpan' => 60,//指定的时间间隔 单位:s
        'sendSpan' => 60,//两次报警的最小时间间隔。防止报警频率过高 单位: s
    ];

    public $delayConf = [
        'timeSpan' => 3600,//判断超时的界限 单位: s
        'recover' => 10,//判断恢复超时的界限 单位: s
        'sendSpan' => 1800, //两次报警的最小时间间隔,防止报警频率过高 单位: s
    ];

    //是否检查延迟报警
    public $needCheckDelay = true;

    //是否需要检查发送失败报警
    public $needCheckErr = true;

    public $sendMailInstance;

    public $parseMessageDate;

    /**
     * 记录发送失败与日志延迟的信息
     * @var array
     * [
     *     'errors' => [
     *         'times' => 1, //一分钟内发送失败的次数
     *         'firstTime' => timestamp, //第一次发送失败的时间
     *         'lastSendEmailTime' => timestamp,//最近一次发送报警的时间
     *      ],
     *     'delay'  => [
     *         'times' => 1, //已发送报警的次数
     *         'lastSendEmailTime' => timestamp,//最近一次发送报警的时间
     *      ],
     * ]
     */
    protected $errors = [];

    protected $messages = []; //['message' => 'message', 'level' => 'level','tag' => 'tag', 'timestamp' => '']

    protected static $timeNum = [];

    protected static $seqId = '';

    public function init()
    {
        parent::init();
        if(!$this->sendMailInstance){
            $this->needCheckDelay = false;
            $this->needCheckErr = false;
            return;
        }
        if(is_string($this->sendMailInstance)){
            $this->sendMailInstance = \Yii::$app->get($this->sendMailInstance);
        }elseif (is_array($this->sendMailInstance)){
            $this->sendMailInstance = \Yii::createObject($this->sendMailInstance);
        }

        if(!($this->sendMailInstance instanceof Component)){
            throw new \Exception('class sendMail is invalid');
        }

        if(!is_callable($this->parseMessageDate)){
            $this->needCheckDelay = false;
        }
    }

    abstract public  function export();

    public function collect(array $message)
    {
        $this->messages = array_merge($this->messages, $this->messageFilter($message));
        $count = count($this->messages);

        if($count < 1) return ;

        $this->export();
        $this->messages = [];
    }

    protected function messageFilter(array $messages)
    {
        $_message = [];
        foreach ($messages as $message) {
            if(! in_array($message['tag'], $this->tags) or ! in_array($message['level'], $this->levels)) {
                continue;
            }
            if(!is_string($message['message']) and !is_numeric($message['message'])){
                $message['message'] = json_encode($message['message'], JSON_UNESCAPED_UNICODE);
            }
            $_message[] = $message;
        }

        return $_message;
    }

    protected function formatMessage($message)
    {
        $dateTime = $this->getTime($message['timestamp']);
        $seqId = ArrayHelper::getValue($message, 'seqId','');

        return sprintf("%s\t%s\t%s\t%s\t%s", $dateTime, $seqId, $message['level'], $message['tag'], $message['message']);
    }

    protected function getTime($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    protected function checkErr($err){
        $currentTime = time();
        $num = ArrayHelper::getValue($this->errors, 'error.times', 0);
        $firstTime = ArrayHelper::getValue($this->errors, 'error.firstTime', $currentTime);
        $lastSendEmailTime = ArrayHelper::getValue($this->errors, 'error.lastSendEmailTime', 0);
        $timeSpan = $currentTime - $firstTime;
        $sendSpan = $currentTime - $lastSendEmailTime;

        if($num >= $this->errConf['times'] and $timeSpan <= $this->errConf['timeSpan'] and $sendSpan >= $this->errConf['sendSpan']){
            \Yii::$app->get('email')->sendEmail('日志报警-发送失败', $err);
            $this->errors['error'] = ['lastSendEmailTime' => $currentTime];
        }elseif($timeSpan > $this->errConf['timeSpan'] or $sendSpan < $this->errConf['sendSpan']){//超过指定时间内，不满足times条件 置0
            $this->errors['error'] = ['times' => 1, 'firstTime' => $currentTime, 'lastSendEmailTime' => $lastSendEmailTime];
        }else{//否则是num次数不够
            $this->errors['error'] = ['times' => $num + 1, 'firstTime' => $firstTime, 'lastSendEmailTime' => $lastSendEmailTime];
        }

        return true;
    }

    protected function checkDelay($time, $tag){
        //无效的时间无法判断是否是延迟
        if($time <= 0) return false;

        $currentTime = time();
        $num = ArrayHelper::getValue($this->errors, 'delay.times', 0);
        $lastSendEmailTime = ArrayHelper::getValue($this->errors, 'delay.lastSendEmailTime', 0);
        $delayTime = $currentTime - $time;
        $sendSpan = $currentTime - $lastSendEmailTime;

        if($delayTime <= $this->delayConf['recover']){//延迟恢复
            if($num > 0){
                $this->errors['delay'] = [];
                \Yii::$app->get('email')->sendEmail('日志报警-日志延迟', $tag.':日志延迟恢复!');
            }

            return true;
        }
        if($delayTime >= $this->delayConf['timeSpan'] and $sendSpan >= $this->delayConf['sendSpan']){//满足延迟报警条件
            $this->errors['delay'] = ['times' => $num + 1, 'lastSendEmailTime' => $currentTime];
            \Yii::$app->get('email')->sendEmail('日志报警-日志延迟', $tag.'-日志延迟,落地时间:'.date('Y-m-d H:i:s', $time).',发送时间:'.date('Y-m-d H:i:s', $currentTime));
        }

        return true;
    }
}