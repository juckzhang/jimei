<?php
namespace console\controllers;

use common\models\mysql\DistributionModel;
use common\models\mysql\OrderModel;
use common\helpers\ClientHelper;
use common\models\mysql\PrePaymentModel;
use console\services\OrderService;
use yii\helpers\ArrayHelper;

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

    public function actionRsyncOrder($startTime = null, $endTime = null)
    {
        $pageno = 1;
        //获取制单开始时间
        $startTime = PrePaymentModel::find()->max('createtime') ?: date('Y-m-d H:i:s', strtotime("-1days"));
        $endTime = date('Y-m-d H:i:s');
        $orderService = OrderService::getService();
        while(true){
            $data = ClientHelper::orderList([
                'pageno' => $pageno,
                'pagesize' => 100,
                'orderstatus' => 'audit',
                'starttime' => $startTime,
                'endtime' => $endTime,
            ]);

            $orderList = ArrayHelper::getValue($data, 'orderlist', []);
            if(empty($orderList)){
                break;
            }

            $orderService->syncOrder($orderList);

            if(count($orderList) < 100){
                break;
            }

            $pageno ++;
        }
    }
}