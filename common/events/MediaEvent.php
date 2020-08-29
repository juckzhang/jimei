<?php
namespace common\events;

use yii\base\Event;

class MediaEvent extends Event{
    public $mediaId = null;
    public $userId = null;
    public $operationId = null;
}