<?php
namespace backend\events;

use yii\base\Event;

class CheckEvent extends Event{
    public $type;

    public $sources = [];
}