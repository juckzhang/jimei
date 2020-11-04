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
use common\models\mysql\ThemeModel;
use yii\helpers\ArrayHelper;

class OrderService extends BackendService
{
    public function OrderList($basid,$page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = OrderModel::find()
            ->where(['base_id' => $basid])
            ->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')])
            ->andFilterWhere([
                'or',
                ['like', 'order_id',ArrayHelper::getValue($other, 'keyword')],
                ['like', 'suitecode',ArrayHelper::getValue($other, 'keyword')],
                ['like', 'goodsname',ArrayHelper::getValue($other, 'keyword')],
                ['like', 'eshopskuname',ArrayHelper::getValue($other, 'keyword')],
                ['like', 'checkcode',ArrayHelper::getValue($other, 'keyword')],
                ['like', 'shopname',ArrayHelper::getValue($other, 'keyword')],
            ]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy(['suitecode' => SORT_ASC, 'order_id' => SORT_DESC])
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

    public function BaseOrderList($baseId,$page, $prePage){
        // 获取base订单
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $baseList = DistributionModel::find()->where(['id' => $baseId])->asArray()->one();
        $data = ['pageCount' => 0,'items' => [],'dataCount' => 0, 'sn' => $baseList['sn']];
        $models = OrderModel::find()->where(['base_id' => $baseId,])
            ->with('brand')
            ->with('phone')
            ->with('material')
            ->with('color')
            ->with('customer')
            ->with('theme')
            ->with('relat');
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);
        $items = $models->orderBy(['suitecode' => SORT_ASC,'order_id' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();

        $dataList = [];
        foreach ($items as $item){
            $templateUrl = ArrayHelper::getValue($item, 'theme.template_url');
            if($templateUrl) $templateUrl = \Yii::$app->params['picUrlPrefix'] . $templateUrl;

            $borderUrl = ArrayHelper::getValue($item, 'relat.border_url');
            if($borderUrl) $borderUrl = \Yii::$app->params['picUrlPrefix'] . $borderUrl;
            $status = 0;
            if($item['status'] == 2
                or ArrayHelper::getValue($item,'relat.status') == 2
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
                'template_url' => $templateUrl,
                'brand' => ArrayHelper::getValue($item, 'brand.name'),
                'modal' => ArrayHelper::getValue($item, 'phone.modal'),
                'canvas_type' => ArrayHelper::getValue($item, 'phone.canvas_type'),
                'width' => ArrayHelper::getValue($item, 'phone.width'),
                'height' => ArrayHelper::getValue($item, 'phone.height'),
                'material' => ArrayHelper::getValue($item, 'material.name'),
                'left' => ArrayHelper::getValue($item, 'relat.left', 0),
                'top' => ArrayHelper::getValue($item, 'relat.top', 0),
                'border_url' => $borderUrl,
                'color' => ArrayHelper::getValue($item, 'color.name'),
                'status' => $item['status'] ?: $status,
            ];
        }
        $data['items'] = $dataList;

        return $data;
    }

    public function reparseOrder($id){
        $orders = OrderModel::find()->where(['id' => $id])->all();
        foreach ($orders as $order){
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
            if(!$brand or !$phone or !$customer or !$color or !$material or !$theme) $status = 2;
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

    public function DistributionList($page,$prePage,$order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = DistributionModel::find()
            ->where(['!=','status' , DistributionModel::STATUS_DELETED])
            ->andFilterWhere(['like','sn',ArrayHelper::getValue($other, 'keyword')])
            ->andFilterWhere(['task_status' => ArrayHelper::getValue($other, 'task_status')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function editDistribution($data){
        $id = ArrayHelper::getValue($data, 'id');
        $model = $this->editInfo($id, DistributionModel::className());
        $batchData = [];
        if($model){
            //同步订单数据
            $page = 1;
            while (true){
                $res = ClientHelper::rsyncOrder([
                    'pageno' => $page,
                    'pagesize' => 100,
                    'distprintsno' => $model->sn,
                ]);
                $orders = ArrayHelper::getValue($res, 'orders', []);
                foreach ($orders as $order){
                    $num = ArrayHelper::getValue($order,'qty', 1);
                    $meal = ArrayHelper::getValue($order, 'suites.0', []);
                    $mealCode = $order['lcmccode'];
                    $except = true;
                    if($meal and $meal['SuiteCode']){
                        $mealCode = $meal['SuiteCode'];
                        $except = false;
                    }
                    while ($num > 0){
                        $batchData[] = $this->parseOrder($order, $model->id, $mealCode, $except);
                        --$num;
                    }
                }
                if(!$orders) return false;
                $ordertotalcount = ArrayHelper::getValue($res, 'ordertotalcount', 0);
                ++$page;
                if($page > (ceil($ordertotalcount / 100))){
                    break;
                }
            }
        }

        $filed = [
            'order_id', 'base_id', 'print_flag', 'is_refund',
            'suitecode', 'mobile_id', 'brand_id','customer_id',
            'theme_id', 'color_id', 'material_id', 'create_time',
            'update_time', 'goodsname', 'lcmccode', 'mccode',
            'eshopskuname','checkcode', 'shopname','num', 'status',
        ];
        if($batchData){
            //$batchData = $this->sortOrder($batchData);
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                OrderModel::deleteAll(['base_id' => $model->id]);
                \Yii::$app->db->createCommand()->batchInsert(OrderModel::tableName(),$filed,$batchData)->execute();
                $transaction->commit();
                return 200;
            }catch (\Exception $e){
                $transaction->rollBack();
                return CodeConstant::DISTRIBUTION_RSYNC_FAILED;
            }
        }

        return CodeConstant::DISTRIBUTION_NOT_ORDER;
    }

    private function parseOrder($order, $sn, $mealCode, $except = false){
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
        if(!$brand or !$phone or !$customer or !$color or !$material or !$theme or $except) $status = 2;
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
            'eshopskuname' => ArrayHelper::getValue($order,'eshopskuname',''),
            'checkcode' => ArrayHelper::getValue($order,'checkcode',''),
            'shopname' => ArrayHelper::getValue($order,'shopname',''),
            'num' => $order['qty'],
            'status' => $status,
        ];
    }

    private function sortOrder($data){
        ArrayHelper::multisort($data, 'goodsname');

        $res = $ret = [];
        foreach ($data as $item){
            $orderId = $item['order_id'];
            if(isset($res[$orderId])){
                $res[$orderId][] = $item;
            }else{
                $res[$orderId] = [$item];
            }
        }

        foreach ($res as $item){
            $ret = ArrayHelper::merge($ret, $item);
        }

        return $ret;
    }
}

