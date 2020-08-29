<?php
namespace backend\services;

use common\models\mysql\DistributionModel;
use common\models\mysql\OrderModel;
use backend\services\base\BackendService;

class OrderService extends BackendService
{
    public function OrderList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = OrderModel::find()
            ->where(['!=','status' , OrderModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function editOrder($id)
    {
        return $this->editInfo($id,OrderModel::className());
    }

    public function deleteOrder($id)
    {
        return $this->deleteInfo($id,OrderModel::className());
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
            ->asArray()
            ->all();

        return ['sn' => $baseList['sn'], 'items' => $items];
    }
}

