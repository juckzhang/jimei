<?php
namespace common\components\log;

use yii\helpers\ArrayHelper;

class FileTarget extends Target {
    const DS = DIRECTORY_SEPARATOR;

    public $dirMode = 0775;
    public $filePath; //日志目录
    public $commonPrefix; //日志文件公共统一前缀
    public $postFix = 'Ymd'; //后缀名
    public $delimiter = '_';
    public $tag2File = [];

    public function export()
    {
        $_message = [];
        foreach ($this->messages as $message) {
            $tag = $message['tag'];
            $text = $this->formatMessage($message);
            $_message[$tag] = ArrayHelper::getValue($_message, $tag, '') . $text .PHP_EOL;
        }

        foreach ($_message as $tag => $text) {
            $fileName = $this->getFileName($tag);
            $dirName = dirname($fileName);
            if(! is_dir($dirName))
                @mkdir($dirName, $this->dirMode, true);

            @file_put_contents($fileName, $text, FILE_APPEND | LOCK_EX);
        }
    }

    private function getFileName($tag)
    {
        $tag2File = ArrayHelper::getValue($this->tag2File, $tag, $tag).$this->delimiter;
        $fileName = $this->commonPrefix . $this->delimiter . $tag2File . date($this->postFix) . '.log';
        return \Yii::getAlias($this->filePath . static::DS . $fileName);
    }
}
