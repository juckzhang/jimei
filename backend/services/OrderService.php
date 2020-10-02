<?php
namespace backend\services;

use common\models\mysql\DistributionModel;
use common\models\mysql\OrderModel;
use backend\services\base\BackendService;
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
}

