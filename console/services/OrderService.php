<?php
namespace console\services;

use common\helpers\ClientHelper;
use common\models\mysql\CustomerModel;
use common\models\mysql\PrePaymentModel;
use common\models\mysql\ThemeModel;
use console\services\base\ConsoleService;
use yii\helpers\ArrayHelper;

class OrderService extends ConsoleService
{
    //拉取预扣款订单列表
    public function syncOrder($orderList = []){
        $dataList = $columns = [];
        foreach($orderList as $order){
            $mealCode = $order['LCMCCode'];
            $customerCode = substr($mealCode, 9, 2);
            $themeCode = substr($mealCode, 11);
            $customer = CustomerModel::find()->where(['barcode' => $customerCode])->one();
            $theme = ThemeModel::find()->where([
                'customer_id' => ArrayHelper::getValue($customer, 'id', 0),
                'barcode' => $themeCode,
            ])->one();

            $customer_id = $theme_id = 0;
            $payment_freight = $payment_total = 0;
            //获取价格
            if($theme){
                $theme_id = $theme->id;
            }
            if($customer){
                $customer_id = $customer->id;
                $payment_freight = $order['FreightTotal'] + $this->logistic($customer, $order['LogisticsName']);
                $payment_total = $theme->price * $order['Qty'];
            }

            //存储数据
            $dataList[] = $this->formatOrder($order, [
                'payment_freight' => $payment_freight,
                'customer_id' => $customer_id,
                'theme_id' => $theme_id,
                'payment_total' => $payment_total,
            ]);
            if(count($columns) == 0){
                $columns = array_keys($dataList[0]);
            }
        }
        try {
            \Yii::$app->db->createCommand()->batchInsert(PrePaymentModel::tableName(), $columns, $dataList)->execute();
            \Yii::$app->bizLog->log([
                'orderNum' => $orderList,
                'orderList' => $dataList,
            ], 'req', 'Info');
        }catch (\Exception $e){
            \Yii::$app->bizLog->log([
                'error' => $e->getMessage(),
                'orderNum' => $orderList,
                'orderList' => $dataList,
            ], 'req', 'Error');
        }
    }

    private function formatOrder($order, &$extData = [])
    {
        $extData['billnO'] = $order['BillNO'];
        $extData['did'] = $order['DID'];
        $extData['logisticsname'] = $order['LogisticsName'];
        $extData['billcode'] = $order['BillCode'];
        $extData['billflag'] = $order['BillFlag'];
        $extData['logisticsname'] = $order['LogistBTypeName'];
        $extData['eshopbillcode'] = $order['EShopBillCode'];
        $extData['eshopname'] = $order['EShopName'];
        $extData['createtime'] = $order['CreateTime'];
        $extData['paytime'] = $order['PayTime'];
        $extData['ecreatetime'] = $order['ECreateTime'];
        $extData['eshopskuname'] = $order['EShopSKUName'];
        $extData['lcmccode'] = $order['LCMCCode'];
        $extData['qty'] = $order['Qty'];
        $extData['price'] = $order['Price'];
        $extData['total'] = $order['Total'];
        $extData['islocked'] = $order['IsLocked'];
        $extData['refundstatus'] = $order['RefundStatus'];
        $extData['freighttotal'] = $order['FreightTotal'];
        $extData['create_time'] = time();
        $extData['update_time'] = time();
        $extData['finance_status'] = 0;

        if(!$extData['customer_id'] or !$extData['theme_id']){
            $extData['finance_status'] = 1; //信息不完整
        }

        return $extData;
    }

    //财审
    public function financeAuth(){
        while(true){
            $dataList = PrePaymentModel::find()->select(['billno'])
            ->groupBy(['billno'])
            ->where(['finance_status' => 0])->limit(100)->column();
        }
        
    }

    private function _finance($billnoList = []){
        if(empty($billnoList) or count($billnoList) <=0 ) return;

        $res = ClientHelper::FinanceAuth(['billNoList' => $billnoList]);
        $errororderlist = ArrayHelper::getValue($res, 'errororderlist', []);

        foreach ($errororderlist as $order) {
            $prePayOrder = PrePaymentModel::findOne(['billno' => $order['BillNO'], 'billcode' => $order['BillCode']]);
            $payment_freight = $prePayOrder->payment_freight;
            $payment_total = $prePayOrder->payment_total;
            $customer = CustomerModel::findOne(['id' => $prePayOrder->customer_id]);
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                CustomerModel::updateAllCounters(['lock_balance' => 0 - $payment_freight - $payment_total], ['customer_id' => $customer->id]);
                \Yii::$app->db->createCommand()->delete(PrePaymentModel::tableName(), [
                    'billno' => $order['BillNO'], 
                    'billcode' => $order['BillCode']
                ])->execute();
                $transaction->commit();
            }catch (\Exception $e){
                $transaction->rollBack();
                continue;
            }
        }

        return;
    }

    private function logistic($customer, $name = '')
    {
        $diff = 0;
        switch($name){
            case "顺丰速递":
                $diff = $customer->sf_diff;
                break;
            default:
                $diff = 0;
        }

        return $diff;
    }
}