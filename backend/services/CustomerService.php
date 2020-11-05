<?php
namespace backend\services;

use common\models\mysql\CustomerModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class CustomerService extends BackendService
{
    // 机型
    public function CustomerList($page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = CustomerModel::find()
            ->where(['!=','status' , CustomerModel::STATUS_DELETED])
            ->andFilterWhere(['id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['like','name',ArrayHelper::getValue($other, 'keyword')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->offset($offset)
                ->all();

        return $data;
    }
}

