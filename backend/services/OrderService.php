<?php
namespace backend\services;

use common\helpers\ClientHelper;
use common\models\mysql\BrandModel;
use common\models\mysql\ColorModel;
use common\models\mysql\CustomerModel;
use common\models\mysql\DistributionModel;
use common\models\mysql\MaterialModel;
use common\models\mysql\OrderModel;
use backend\services\base\BackendService;
use common\models\mysql\PhoneModel;
use common\models\mysql\ThemeMaterialModel;
use common\models\mysql\ThemeModel;
use yii\helpers\ArrayHelper;

class OrderService extends BackendService
{
    public function OrderList($basid,$page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = OrderModel::find()
            ->where(['!=','status' , OrderModel::STATUS_DELETED])
            ->andFilterWhere(['base_id' => $basid])
            ->andFilterWhere(['like', 'order_id',ArrayHelper::getValue($other, 'keyword')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->offset($offset)
                ->with('phone')
                ->with('material')
                ->with('color')
                ->with('theme')
                ->with('sn')
                ->asArray()
                ->all();

        return $data;
    }

    public function BaseOrderList($baseId){
        // 获取base订单
        $baseList = DistributionModel::find()->where(['id' => $baseId])->asArray()->one();
        $items = OrderModel::find()->where([
                'status' => OrderModel::STATUS_ACTIVE,
                'base_id' => $baseId,
            ])->with('phone')
            ->with('material')
            ->with('color')
            ->with('theme')
            ->with('relat')
            ->asArray()
            ->all();

        $dataList = [];
        foreach ($items as $item){
            $templateUrl = ArrayHelper::getValue($item, 'theme.template_url');
            if($templateUrl) $templateUrl = \Yii::$app->params['picUrlPrefix'] . $templateUrl;

            $borderUrl = ArrayHelper::getValue($item, 'relat.border_url');
            if($borderUrl) $borderUrl = \Yii::$app->params['picUrlPrefix'] . $borderUrl;

            $dataList[] = [
                'barcode' => $item['barcode'],
                'theme' => ArrayHelper::getValue($item, 'theme.name'),
                'template_url' => $templateUrl,
                'modal' => ArrayHelper::getValue($item, 'phone.modal'),
                'canvas_type' => ArrayHelper::getValue($item, 'phone.canvas_type') ,
                'width' => ArrayHelper::getValue($item, 'phone.width'),
                'height' => ArrayHelper::getValue($item, 'phone.height'),
                'material' => ArrayHelper::getValue($item, 'material.name'),
                'left' => ArrayHelper::getValue($item, 'relat.left', 0),
                'top' => ArrayHelper::getValue($item, 'relat.top', 0),
                'border_url' => $borderUrl,
                'color' => ArrayHelper::getValue($item, 'color.name'),
                'status' => $item['status'],
            ];
        }
        return ['sn' => $baseList['sn'], 'items' => $dataList];
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
                $batchData = array_merge($batchData, $this->parseOrder($model->id,$orders));
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
            'barcode', 'mobile_id', 'brand_id','customer_id',
            'theme_id', 'color_id', 'material_id', 'create_time',
            'update_time', 'goodsname', 'lcmccode', 'mccode', 'num', 'status'
        ];
        if($batchData){
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                OrderModel::deleteAll(['base_id' => $model->id]);
                \Yii::$app->db->createCommand()->batchInsert(OrderModel::tableName(),$filed,$batchData)->execute();
                $transaction->commit();
                return true;
            }catch (\Exception $e){
                $transaction->rollBack();
                return false;
            }
        }
    }

    private function parseOrder($sn, $orders){
        $ret = [];
        $now = time();
        foreach ($orders as $order){
            foreach($order['suites'] as $meal){
                $mealCode = $meal['SuiteCode'];
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
                $ret[] = [
                    'order_id' => $order['billcode'],
                    'base_id' => $sn,
                    'print_flag' => (int)$order['isdistconfirmprint'],
                    'is_refund' => (int)$order['isrefund'],
                    'barcode' => $mealCode,
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
                    'num' => $order['qty'],
                    'status' => $status,
                ];
            }
        }

        return $this->sortOrder($ret);
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

