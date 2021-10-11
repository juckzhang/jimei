<?php
namespace console\controllers;

use common\models\mysql\DistributionModel;
use common\models\mysql\OrderModel;
use common\helpers\ClientHelper;
use common\models\mysql\PrePaymentModel;
use console\services\OrderService;
use yii\helpers\ArrayHelper;

class OrderController extends BaseController{

    public function actionTest()
    {
        $data = ClientHelper::orderList([
            'pageno' => 1,
            'pagesize' => 100,
            'orderstatus' => 'audit',
            'starttime' => date('Y-m-d H:i:s', strtotime("-1days")),
            'endtime' => date('Y-m-d H:i:s'),
        ]);

        echo json_encode($data);
    }

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
        $total = 0;
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

            $total += count($orderList);
            $orderService->syncOrder($orderList);

            if(count($orderList) < 100){
                break;
            }

            $pageno ++;
        }
        echo 'startTime:'.$startTime.'  endTime:'.$endTime.'  totalNum:'.$total;
        echo PHP_EOL;
        \Yii::$app->bizLog->log([
            'startTime' => $startTime,
            'endTime' => $endTime,
            'totalNum' => $total,
        ], 'req', 'Info');

        //财务审核
        $orderService->financeAuth();
    }

    public function actionFinance(){
        $orderService = OrderService::getService();
        $orderService->financeAuth();
    }
}