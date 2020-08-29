<?php
namespace backend\services;

use common\models\mysql\PhoneModel;
use backend\services\base\BackendService;

class PhoneService extends BackendService
{
    public function PhoneList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = PhoneModel::find()
            ->where(['!=','status' , PhoneModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->offset($offset)
                ->with('brand')
                ->all();

        return $data;
    }

    public function editPhone($id)
    {
        return $this->editInfo($id,PhoneModel::className());
    }

    public function deletePhone($id)
    {
        return $this->deleteInfo($id,PhoneModel::className());
    }
}

