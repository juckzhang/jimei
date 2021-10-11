<?php

namespace backend\services;

use common\constants\CodeConstant;
use common\helpers\ClientHelper;
use common\models\mysql\BrandModel;
use common\models\mysql\ColorModel;
use common\models\mysql\CustomerModel;
use common\models\mysql\DistributionModel;
use common\models\mysql\MaterialModel;
use common\models\mysql\OrderModel;
use backend\services\base\BackendService;
use common\models\mysql\PhoneModel;
use common\models\mysql\PrePaymentModel;
use common\models\mysql\ThemeModel;
use yii\helpers\ArrayHelper;

class OrderService extends BackendService
{
    public function OrderList($basid, $page, $prePage, array $order = [], $other = [])
    {
        list($offset, $limit) = $this->parsePageParam($page, $prePage);
        $order = ['suitecode' => SORT_ASC, 'order_id' => SORT_DESC];
        if (ArrayHelper::getValue($other, 'add_type') == '2') $order = ['jimei_order.id' => SORT_ASC];
        $data = ['pageCount' => 0, 'dataList' => [], 'dataCount' => 0];
        $status = ArrayHelper::getValue($other, 'status');
        $models = OrderModel::find()
            ->where(['base_id' => $basid])
            //            ->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')])
            ->andFilterWhere([
                'or',
                ['like', 'order_id', ArrayHelper::getValue($other, 'keyword')],
                ['like', 'suitecode', ArrayHelper::getValue($other, 'keyword')],
                ['like', 'goodsname', ArrayHelper::getValue($other, 'keyword')],
                ['like', 'eshopskuname', ArrayHelper::getValue($other, 'keyword')],
                ['like', 'checkcode', ArrayHelper::getValue($other, 'keyword')],
                ['like', 'shopname', ArrayHelper::getValue($other, 'keyword')],
            ]);
        if ($status === '0' or $status == 2) {
            $filter = 'or';
            if ($status === '0') $filter = 'and';
            $models = $models->join('left join', 'jimei_theme', 'jimei_order.theme_id=jimei_theme.id')
                ->join('left join', 'jimei_phone_material_relation', 'jimei_order.mobile_id=jimei_phone_material_relation.mobile_id and jimei_order.material_id=jimei_phone_material_relation.material_id')
                ->andWhere([
                    $filter,
                    ['jimei_order.status' => $status],
                    ['jimei_theme.status' => $status],
                    ['jimei_phone_material_relation.status' => $status],
                ]);
        } else {
            $models = $models->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')]);
        }
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'], $limit);

        if ($data['pageCount'] > 0 and $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->offset($offset)
                ->with('brand')
                ->with('phone')
                ->with('material')
                ->with('color')
                ->with('customer')
                ->with('theme')
                ->with('sn')
                ->with('relat')
                ->asArray()
                ->all();

        return $data;
    }

    public function BaseOrderList($baseId, $page, $prePage)
    {
        // 获取base订单
        list($offset, $limit) = $this->parsePageParam($page, $prePage);
        $baseList = DistributionModel::find()->where(['id' => $baseId])->asArray()->one();
        $order = ['suitecode' => SORT_ASC, 'order_id' => SORT_DESC];
        if ($baseList['add_type'] == 2) $order = ['id' => SORT_ASC];
        $data = ['pageCount' => 0, 'items' => [], 'dataCount' => 0, 'sn' => $baseList['sn']];
        $models = OrderModel::find()->where(['base_id' => $baseId,])
            ->with('brand')
            ->with('phone')
            ->with('material')
            ->with('color')
            ->with('customer')
            ->with('theme')
            ->with('relat');
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'], $limit);
        $items = $models->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();

        $dataList = [];
        foreach ($items as $item) {
            $status = 0;
            if (
                $item['status'] == 2
                or ArrayHelper::getValue($item, 'relat.status') == 2
                or ArrayHelper::getValue($item, 'phone.status') == 2
                or !ArrayHelper::getValue($item, 'relat')
            )
                $status = 1;
            $dataList[] = [
                'id' => $item['id'],
                'barcode' => sprintf(
                    "%s%s%s%s%s%s",
                    ArrayHelper::getValue($item, 'brand.barcode'),
                    ArrayHelper::getValue($item, 'phone.barcode'),
                    ArrayHelper::getValue($item, 'material.barcode'),
                    ArrayHelper::getValue($item, 'color.barcode'),
                    ArrayHelper::getValue($item, 'customer.barcode'),
                    ArrayHelper::getValue($item, 'theme.barcode')
                ),
                'theme' => ArrayHelper::getValue($item, 'theme.name'),
                'template_url' => $this->handlerPic($item, 'theme.template_url'),
                'left_template_url' => $this->handlerPic($item, 'theme.left_template_url'),
                'right_template_url' => $this->handlerPic($item, 'theme.right_template_url'),
                'brand' => ArrayHelper::getValue($item, 'brand.name'),
                'modal' => ArrayHelper::getValue($item, 'phone.modal'),
                'canvas_type' => ArrayHelper::getValue($item, 'phone.canvas_type'),
                'width' => ArrayHelper::getValue($item, 'relat.width'),
                'height' => ArrayHelper::getValue($item, 'relat.height'),
                'fat' => ArrayHelper::getValue($item, 'relat.fat'),
                'material' => ArrayHelper::getValue($item, 'material.name'),
                'left' => ArrayHelper::getValue($item, 'relat.left', 0),
                'top' => ArrayHelper::getValue($item, 'relat.top', 0),
                'side_radian' => ArrayHelper::getValue($item, 'relat.side_radian', 0),
                'fixture_num' => ArrayHelper::getValue($item, 'relat.fixture_num', 0),
                'border_url' => $this->handlerPic($item, 'relat.border_url'),
                'left_border_url' => $this->handlerPic($item, 'relat.left_border_url'),
                'right_border_url' => $this->handlerPic($item, 'relat.right_border_url'),
                'color' => ArrayHelper::getValue($item, 'color.name'),
                'customer_name' => ArrayHelper::getValue($item, 'customer.name'),
                'status' => $item['status'] ?: $status,
            ];
        }
        $data['items'] = $dataList;

        return $data;
    }

    public function reparseOrder($id)
    {
        $orders = OrderModel::find()->where(['id' => $id])->all();
        foreach ($orders as $order) {
            $mealCode = $order['suitecode'];
            $brandCode = substr($mealCode, 0, 2);
            $phoneCode = substr($mealCode, 2, 3);
            $materialCode = substr($mealCode, 5, 2);
            $colorCode = substr($mealCode, 7, 2);
            $customerCode = substr($mealCode, 9, 2);
            $themeCode = substr($mealCode, 11);
            $brand = BrandModel::find()->where(['barcode' => $brandCode])->asArray()->one();
            $phone = PhoneModel::find()->where([
                'brand_id' => ArrayHelper::getValue($brand, 'id', 0),
                'barcode' => $phoneCode,
            ])->asArray()->one();
            $customer = CustomerModel::find()->where(['barcode' => $customerCode])->asArray()->one();
            $color = ColorModel::find()->where(['barcode' => $colorCode])->asArray()->one();
            $material = MaterialModel::find()->where(['barcode' => $materialCode])->asArray()->one();
            $theme = ThemeModel::find()->where([
                'customer_id' => ArrayHelper::getValue($customer, 'id', 0),
                'barcode' => $themeCode,
            ])->asArray()->one();
            $status = 0;
            if (!$brand or !$phone or !$customer or !$color or !$material or !$theme) $status = 2;
            $order->mobile_id = ArrayHelper::getValue($phone, 'id', 0);
            $order->brand_id = ArrayHelper::getValue($brand, 'id', 0);
            $order->customer_id = ArrayHelper::getValue($customer, 'id', 0);
            $order->theme_id = ArrayHelper::getValue($theme, 'id', 0);
            $order->color_id = ArrayHelper::getValue($color, 'id', 0);
            $order->material_id = ArrayHelper::getValue($material, 'id', 0);
            $order->status = $status;
            $order->save();
        }

        return true;
    }

    public function DistributionList($page, $prePage, $order = [], $other = [])
    {
        list($offset, $limit) = $this->parsePageParam($page, $prePage);
        $data = ['pageCount' => 0, 'dataList' => [], 'dataCount' => 0];

        $models = DistributionModel::find()
            ->where(['!=', 'status', DistributionModel::STATUS_DELETED])
            ->andFilterWhere(['like', 'sn', ArrayHelper::getValue($other, 'keyword')])
            ->andFilterWhere(['add_type' => ArrayHelper::getValue($other, 'add_type')])
            ->andFilterWhere(['task_status' => ArrayHelper::getValue($other, 'task_status')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'], $limit);

        if ($data['pageCount'] > 0 and $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function editDistribution($data)
    {
        $id = ArrayHelper::getValue($data, 'id');
        $model = $this->editInfo($id, DistributionModel::className());
        if ($model->add_type != '1') return 200;
        $batchData = [];
        if ($model) {
            //同步订单数据
            $page = 1;
            while (true) {
                $res = ClientHelper::rsyncOrder([
                    'pageno' => $page,
                    'pagesize' => 100,
                    'distprintsno' => $model->sn,
                ]);
                $orders = ArrayHelper::getValue($res, 'orders', []);
                foreach ($orders as $order) {
                    $num = ArrayHelper::getValue($order, 'qty', 1);
                    $meal = ArrayHelper::getValue($order, 'suites.0', []);
                    $mealCode = $order['lcmccode'];
                    $except = true;
                    if ($meal and $meal['SuiteCode']) {
                        $mealCode = $meal['SuiteCode'];
                        $except = false;
                    }
                    while ($num > 0) {
                        $batchData[] = $this->parseOrder($order, $model->id, $mealCode, $except);
                        --$num;
                    }
                    //扣款
                    $this->deduction($order);
                }
                $ordertotalcount = ArrayHelper::getValue($res, 'ordertotalcount', 0);
                ++$page;
                if ($page > (ceil($ordertotalcount / 100))) {
                    break;
                }
            }
        }

        $filed = [
            'order_id', 'base_id', 'print_flag', 'is_refund',
            'suitecode', 'mobile_id', 'brand_id', 'customer_id',
            'theme_id', 'color_id', 'material_id', 'create_time',
            'update_time', 'goodsname', 'lcmccode', 'mccode',
            'eshopskuname', 'checkcode', 'shopname', 'num', 'wuliu_no', 'eshopbillcode', 'status',
        ];
        $ret = CodeConstant::DISTRIBUTION_NOT_ORDER;
        $starttime = microtime(true);
        $errorMessage = '';
        if ($batchData) {
            //$batchData = $this->sortOrder($batchData);
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                OrderModel::deleteAll(['base_id' => $model->id]);
                \Yii::$app->db->createCommand()->batchInsert(OrderModel::tableName(), $filed, $batchData)->execute();
                $transaction->commit();
                $ret = 200;
            } catch (\Exception $e) {
                $transaction->rollBack();
                $ret = CodeConstant::DISTRIBUTION_RSYNC_FAILED;
                $errorMessage = $e->getMessage();
            }
        }
        $endtime = microtime(true);
        \Yii::$app->bizLog->log([
            'batchData' => $batchData,
            'total' => count($batchData),
            'time' => sprintf("%.3f", ($endtime - $starttime)),
            'errorMessage' => $errorMessage,
        ], 'mysql', 'Info');

        return $ret;
    }

    public function addOrder($baseId, $keyWord)
    {
        if (!$baseId or !$keyWord) {
            return CodeConstant::PARAM_ERROR;
        }
        $starttime = microtime(true);
        $orderList = OrderModel::find()->select('jimei_order.*')
            ->join('left join', 'jimei_base_list', 'jimei_order.base_id=jimei_base_list.id')
            ->where([
                'or',
                ['wuliu_no' => $keyWord],
                ['eshopbillcode' => $keyWord],
            ])
            ->andWhere(['add_type' => 1])
            ->orderBy(['suitecode' => SORT_ASC, 'order_id' => SORT_DESC])
            ->all();
        if (!$orderList) return CodeConstant::ORDER_NOT_FOUND;

        $ret = 200;
        $errorMessage = '';
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($orderList as $order) {
                $order->id = null;
                $order->base_id = $baseId;
                $order->setIsNewRecord(true);
                $order->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $ret = CodeConstant::ORDER_ADD_FAILED;
            $errorMessage = $e->getMessage();
        }

        \Yii::$app->bizLog->log([
            'data' => $orderList,
            'params' => ['base_id' => $baseId, 'keyWord' => $keyWord],
            'total' => count($orderList),
            'time' => sprintf("%.3f", (microtime(true) - $starttime)),
            'errorMessage' => $errorMessage,
        ], 'mysql', 'Info');

        return $ret;
    }

    private function parseOrder($order, $sn, $mealCode, $except = false)
    {
        $brandCode = substr($mealCode, 0, 2);
        $phoneCode = substr($mealCode, 2, 3);
        $materialCode = substr($mealCode, 5, 2);
        $colorCode = substr($mealCode, 7, 2);
        $customerCode = substr($mealCode, 9, 2);
        $themeCode = substr($mealCode, 11);
        $now = time();
        $brand = BrandModel::find()->where(['barcode' => $brandCode])->asArray()->one();
        $phone = PhoneModel::find()->where([
            'brand_id' => ArrayHelper::getValue($brand, 'id', 0),
            'barcode' => $phoneCode,
        ])->asArray()->one();
        $customer = CustomerModel::find()->where(['barcode' => $customerCode])->asArray()->one();
        $color = ColorModel::find()->where(['barcode' => $colorCode])->asArray()->one();
        $material = MaterialModel::find()->where(['barcode' => $materialCode])->asArray()->one();
        $theme = ThemeModel::find()->where([
            'customer_id' => ArrayHelper::getValue($customer, 'id', 0),
            'barcode' => $themeCode,
        ])->asArray()->one();
        $status = 0;
        if (!$brand or !$phone or !$customer or !$color or !$material or !$theme or $except) $status = 2;
        return [
            'order_id' => $order['billcode'],
            'base_id' => $sn,
            'print_flag' => (int)$order['isdistconfirmprint'],
            'is_refund' => (int)$order['isrefund'],
            'suitecode' => $mealCode,
            'mobile_id' => ArrayHelper::getValue($phone, 'id', 0),
            'brand_id' => ArrayHelper::getValue($brand, 'id', 0),
            'customer_id' => ArrayHelper::getValue($customer, 'id', 0),
            'theme_id' => ArrayHelper::getValue($theme, 'id', 0),
            'color_id' => ArrayHelper::getValue($color, 'id', 0),
            'material_id' => ArrayHelper::getValue($material, 'id', 0),
            'create_time' => $now,
            'update_time' => $now,
            'goodsname' => $order['goodsname'],
            'lcmccode' => $order['lcmccode'],
            'mccode' => $order['mccode'],
            'eshopskuname' => ArrayHelper::getValue($order, 'eshopskuname', ''),
            'checkcode' => ArrayHelper::getValue($order, 'checkcode', ''),
            'shopname' => ArrayHelper::getValue($order, 'eshopname', ''),
            'num' => $order['qty'],
            'wuliu_no' => ArrayHelper::getValue($order, 'logistbillcode', ''),
            'eshopbillcode' => ArrayHelper::getValue($order, 'eshopbillcode', ''),
            'status' => $status,
        ];
    }

    private function sortOrder($data)
    {
        ArrayHelper::multisort($data, 'goodsname');

        $res = $ret = [];
        foreach ($data as $item) {
            $orderId = $item['order_id'];
            if (isset($res[$orderId])) {
                $res[$orderId][] = $item;
            } else {
                $res[$orderId] = [$item];
            }
        }

        foreach ($res as $item) {
            $ret = ArrayHelper::merge($ret, $item);
        }

        return $ret;
    }

    //检查订单数据信息完整性
    private function check_order($orderList = [])
    {
        $res = true;
        $customer_id = 0;
        foreach ($orderList as $item) {
            //信息不完整
            if ($item['theme_id'] <= 0 or $item['customer_id'] <= 0) {
                $res = 1;
                \Yii::$app->bizLog->log([
                    'error' => "素材信息不完整",
                    'orderList' => $orderList,
                ], 'req', 'Error');
                break;
            }

            if ($customer_id > 0 and $item['customer_id'] != $customer_id) {
                $res = 1;
                \Yii::$app->bizLog->log([
                    'error' => "订单客户信息不一致",
                    'orderList' => $orderList,
                ], 'req', 'Error');
                break;
            }

            if ($customer_id == 0) {
                $customer_id = $item['customer_id'];
            }
            if ($item['finance_status'] == 4) {
                return 2;
            }
        }

        return true;
    }

    private function deduction($order)
    {
        $billcode = $order['billcode'];
        $orderList = PrePaymentModel::find()
            ->where(['billcode' => $billcode])
            ->asArray()
            ->all();

        //检查订单信息是否完整
        $res = $this->check_order($orderList);
        if ($res == 1) { //信息不完整
            return false;
        } elseif ($res == 2) { //已扣过款
            return true;
        } else { //信息不完整
            return false;
        }

        $customer_id = $kou = 0;
        foreach ($orderList as $item) {
            $customer_id = $item['customer_id'];
            $kou += $item['payment_total'] + $item['payment_freight'];
        }
        if ($customer_id and $kou > 0) {
            $customer = CustomerModel::findOne(['id' => $customer_id]);
            $customer->balance -= $kou;
            $customer->lock_balance -= $kou;
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $customer->update();
                PrePaymentModel::updateAll(['finance_status' => 4], ['billcode' => $billcode]);
                $transaction->commit();
                \Yii::$app->bizLog->log([
                    'data' => $orderList,
                    'total' => count($orderList),
                ], 'mysql', 'Error');
            } catch (\Exception $e) {
                $transaction->rollBack();
                $errorMessage = $e->getMessage();
                \Yii::$app->bizLog->log([
                    'data' => $orderList,
                    'total' => count($orderList),
                    'errorMessage' => $errorMessage,
                ], 'mysql', 'Error');
            }
        }

        return;
    }

    public function preOrderList($page, $prePage, $order = [], $other = [])
    {
        list($offset, $limit) = $this->parsePageParam($page, $prePage);
        $data = ['pageCount' => 0, 'dataList' => [], 'dataCount' => 0];

        $models = PrePaymentModel::find()
            ->where(['!=', 'status', DistributionModel::STATUS_DELETED])
            ->andFilterWhere(['finance_status' => $other['finance_status']]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'], $limit);

        if ($data['pageCount'] > 0 and $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->with('customer')
                ->with('theme')
                ->limit($limit)
                ->offset($offset)
                ->asArray()
                ->all();

        return $data;
    }
}
