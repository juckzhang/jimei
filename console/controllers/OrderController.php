<?php
namespace console\controllers;


use common\models\mysql\DistributionModel;
use common\models\mysql\OrderModel;

class OrderController extends BaseController{

    public function actionClearOrder(){
        $createTime = strtotime("-20 day");

        //删除20天的配货单
        $baseNum = DistributionModel::deleteAll(['<=', 'create_time', $createTime]);

        //删除20天前的订单数据
        $orderNum = OrderModel::deleteAll(['<=','create_time', $createTime]);

        \Yii::$app->bizLog->log([
            'baseNum' => $baseNum,
            'orderNum' => $orderNum,
            'create_time' => $createTime,
        ], 'req', 'Info');

        return true;
    }
}