<?php
namespace common\behaviors;

use common\models\mysql\MediaModel;
use common\services\OrderService;
use yii\base\Behavior;

class MediaBehavior extends Behavior{

    public function events()
    {
        return [
            OrderService::AFTER_SCANNED_MEDIA => 'afterScanned',
            OrderService::AFTER_CANCEL_COLLECT_MEDIA => 'afterCancelCollect',
            OrderService::AFTER_COLLECT_MEDIA => 'afterCollect',
            OrderService::AFTER_DOWNLOAD_MEDIA => 'afterDownload',
        ];
    }

    public function afterScanned($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof OrderService)
            $event->sender->scanned($event->mediaId,$event->userId);

        //将数据添加增加1
        MediaModel::updateAllCounters(['play_num' => 1, 'real_play_num' => 1],['id' => $event->mediaId]);
    }

    public function afterCancelCollect($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof OrderService){
            //将数据添加增加1
            MediaModel::updateAllCounters(['collection_num' => -1, 'real_collection_num' => -1],['id' => $event->mediaId]);
        }
    }

    public function afterCollect($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof OrderService){
            //将数据添加增加1
            MediaModel::updateAllCounters(['collection_num' => 1, 'real_collection_num' => 1],['id' => $event->mediaId]);
        }
    }

    public function afterDownload($event)
    {
        if(is_numeric($event->userId) AND $event->sender instanceof OrderService){
            //将数据添加增加1
            MediaModel::updateAllCounters(['download_num' => 1, 'real_download_num' => 1],['id' => $event->mediaId]);
        }
    }
}