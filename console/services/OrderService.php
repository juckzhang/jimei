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
    public function syncOrder($orderList = []){
        $billno = $failedList = [];
        foreach($orderList as $order){
            $mealCode = $order['LCMCCode'];
            $customerCode = substr($mealCode, 9, 2);
            $themeCode = substr($mealCode, 11);
            $customer = CustomerModel::find()->where(['barcode' => $customerCode])->one();
            $theme = ThemeModel::find()->where([
                'customer_id' => ArrayHelper::getValue($customer, 'id', 0),
                'barcode' => $themeCode,
            ])->one();

            if(!$customer or !$theme) continue;
            //获取价格
            $balance = $customer->balance - $customer->lock_balance;
            if($balance <= 0) continue;
            $customer_id = $customer->id;
            $theme_id = $theme->id;
            $payment_freight = $order['FreightTotal'] + $this->logistic($customer, $order['LogisticsName']);
            $payment_total = $theme->price * $order['Qty'];
            if($balance < ($payment_freight + $payment_total)) continue;

            //存储数据
            $pre_payment_order = $this->formatOrder($order, [
                'payment_freight' => $payment_freight, 
                'customer_id' => $customer_id,
                'theme_id' => $theme_id,
                'payment_total' => $payment_total,
            ]);

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                CustomerModel::updateAllCounters(['lock_balance' => $payment_freight + $payment_total], ['customer_id' => $customer_id]);
                \Yii::$app->db->createCommand()->insert(PrePaymentModel::tableName(), $pre_payment_order)->execute();
                $transaction->commit();
                $billno[] = $order['BillNO'];
            }catch (\Exception $e){
                $transaction->rollBack();
                continue;
            }
        }

        $this->financeAuth($billno);
    }

    private function financeAuth($billnoList = []){
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

        return
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