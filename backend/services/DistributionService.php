<?php
namespace backend\services;

use common\models\mysql\DistributionModel;
use backend\services\base\BackendService;

class DistributionService extends BackendService
{
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
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->asArray()->all();

        return $data;
    }

    public function editDistribution($id)
    {
        return $this->editInfo($id,DistributionModel::className());
    }

    public function deleteDistribution($id)
    {
        return $this->deleteInfo($id,DistributionModel::className());
    }
}

