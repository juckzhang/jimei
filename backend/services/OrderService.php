<?php
namespace backend\services;

use common\models\mysql\DistributionModel;
use common\models\mysql\OrderModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class OrderService extends BackendService
{
    public function OrderList($basid,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = OrderModel::find()
            ->where(['!=','status' , OrderModel::STATUS_DELETED])
            ->andFilterWhere(['base_id' => $basid]);

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
            $dataList[] = [
                'barcode' => $item['barcode'],
                'theme' => ArrayHelper::getValue($item, 'theme.name'),
                'template_url' => ArrayHelper::getValue($item, 'theme.template_url'),
                'modal' => ArrayHelper::getValue($item, 'phone.modal'),
                'width' => ArrayHelper::getValue($item, 'phone.width'),
                'height' => ArrayHelper::getValue($item, 'phone.height'),
                'material' => ArrayHelper::getValue($item, 'material.name'),
                'left' => ArrayHelper::getValue($item, 'relat.left', 0),
                'top' => ArrayHelper::getValue($item, 'relat.top', 0),
            ];
        }
        return ['sn' => $baseList['sn'], 'items' => $dataList];
    }

    public function DistributionList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = DistributionModel::find()
            ->where(['!=','status' , DistributionModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }
}

