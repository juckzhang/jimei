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
    public function syncOrder($orderList = [])
    {
        $dataList = $columns = [];
        foreach ($orderList as $order) {
            //判断订单是否已经存在
            if($this->order_exists($order)){
                continue;
            }
            $mealCode = $order['SuiteCode'];
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
            if ($customer) {
                $customer_id = $customer->id;
                $payment_freight = $order['FreightTotal'] + $this->logistic($customer, $order['LogisticsName']);
                if($theme){
                    $payment_total = $theme->price * $order['Qty'];
                    $theme_id = $theme->id;
                }
            }

            if($order['BillFlag'] == '我方承担'){
                $payment_freight = $payment_total = 0;
            }

            //存储数据
            $extData = [
                'payment_freight' => $payment_freight,
                'customer_id' => $customer_id,
                'theme_id' => $theme_id,
                'payment_total' => $payment_total,
            ];
            $dataList[] = $this->formatOrder($order, $extData);
            if (count($columns) == 0) {
                $columns = array_keys($dataList[0]);
            }
        }
        try {
            \Yii::$app->db->createCommand()->batchInsert(PrePaymentModel::tableName(), $columns, $dataList)->execute();
            \Yii::$app->bizLog->log([
                'orderNum' => $orderList,
                'orderList' => $dataList,
            ], 'req', 'Info');
        } catch (\Exception $e) {
            \Yii::$app->bizLog->log([
                'error' => $e->getMessage(),
                'orderNum' => $orderList,
                'orderList' => $dataList,
            ], 'req', 'Error');
        }
    }

    private function order_exists($order){
        return PrePaymentModel::find()
        ->where(['billno' => $order['BillNO'], 'did' => $order['DID']])
        ->exists();
    }

    private function formatOrder($order, &$extData = [])
    {
        $extData['billno'] = $order['BillNO'];
        $extData['suitecode'] = $order['SuiteCode'];
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

        if (!$extData['customer_id'] or !$extData['theme_id']) {
            $extData['finance_status'] = 1; //信息不完整
        }

        return $extData;
    }

    //财审
    public function financeAuth()
    {
        while (true) {
            $dataList = PrePaymentModel::find()->select(['billno'])
                ->groupBy(['billno'])
                ->where(['finance_status' => 0])->limit(100)->column();

            foreach ($dataList as $billno) {
                $orderList = PrePaymentModel::find()
                    ->where(['billno' => $billno])
                    ->asArray()
                    ->all();

                //验证信息完整性
                $fullInfo = $this->check_order($orderList);
                if (!$fullInfo) {
                    continue;
                }

                //扣款
                $this->calculation($orderList);
            }
        }
    }

    //检查订单数据信息完整性
    private function check_order($orderList = [])
    {
        $res = true;
        $customer_id = 0;
        foreach ($orderList as $item) {
            //信息不完整
            if ($item['theme_id'] <= 0 or $item['customer_id'] <= 0) {
                $res = false;
                \Yii::$app->bizLog->log([
                    'error' => "素材信息不完整",
                    'orderList' => $orderList,
                ], 'req', 'Error');
                break;
            }

            if ($customer_id > 0 and $item['customer_id'] != $customer_id) {
                $res = false;
                \Yii::$app->bizLog->log([
                    'error' => "订单客户信息不一致",
                    'orderList' => $orderList,
                ], 'req', 'Error');
                break;
            }

            if ($customer_id == 0) {
                $customer_id = $item['customer_id'];
            }
        }

        return $res;
    }

    //计算预先扣款值.
    private function calculation($orderList = [])
    {
        $theme_ids = [];
        $customer_id = 0;
        $billno = 0;

        foreach ($orderList as $item) {
            if ($customer_id <= 0) {
                $customer_id = $item['customer_id'];
            }
            $theme_ids[] = $item['theme_id'];
            if (!$billno) {
                $billno = $item['billno'];
            }
        }

        //获取客户信息与素材信息
        $customer = CustomerModel::find()->where(['id' => $customer_id])->one();
        $theme_list = ThemeModel::find()->where(['id' => $theme_ids])->all();

        //用户余额
        $balance = $customer->balance - $customer->lock_balance;

        //预扣款金额
        $kou = 0;
        foreach ($theme_list as $item) {
            $kou += $item['payment_freight'] + $item['payment_total'];
        }

        if ($balance - $kou < 0) {
            \Yii::$app->bizLog->log([
                'error' => "余额不足",
                'orderList' => $orderList,
            ], 'req', 'Error');
            return false;
        }

        //调用财审批接口
        try {
            $res = ClientHelper::FinanceAuth(['billnoList' => [$billno]]);
            if ($res === true) {
                $customer->lock_balance += $kou;
                $res = $customer->update();
            }
            \Yii::$app->bizLog->log([
                'error' => "ok",
                'orderList' => $orderList,
            ], 'req', 'Info');
            return true;
        } catch (\Exception $e) {
            \Yii::$app->bizLog->log([
                'error' => $e->getMessage(),
                'orderList' => $orderList,
            ], 'req', 'Error');
            return false;
        }
    }

    private function _finance($billnoList = [])
    {
        if (empty($billnoList) or count($billnoList) <= 0) return;

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
            } catch (\Exception $e) {
                $transaction->rollBack();
                continue;
            }
        }

        return;
    }

    private function logistic($customer, $name = '')
    {
        $diff = 0;
        switch ($name) {
            case "顺丰速递":
                $diff = $customer->sf_diff;
                break;
            default:
                $diff = 0;
        }

        return $diff;
    }
}
