<?php
namespace common\services;

use common\constants\CodeConstant;
use common\helpers\CommonHelper;
use common\models\mysql\OrderModel;
use common\services\base\Service;

class OrderService extends Service
{
    //视频/音频列表
    public function orderList($baseId)
    {
        $models = OrderModel::find()
            ->select([])
            ->where([
                'base_id' => $baseId,
                'status' => MediaModel::STATUS_ACTIVE,
            ])
            ->asArray()
            ->with('phone')
            ->with('theme')
            ->with('material')->all();
        $data['dataList'] = $models;

        return $data;
    }
}