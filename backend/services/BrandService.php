<?php
namespace backend\services;

use common\models\mysql\BrandModel;
use backend\services\base\BackendService;

class BrandService extends BackendService
{
    public function brandList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = BrandModel::find()
            ->where(['!=','status' , BrandModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->asArray()->all();

        return $data;
    }

    public function allList(){
        return BrandModel::find()->where(['!=','status' , BrandModel::STATUS_DELETED])->asArray()->all();
    }

    public function editBrand($id)
    {
        return $this->editInfo($id,BrandModel::className());
    }

    public function deleteBrand($id)
    {
        return $this->deleteInfo($id,BrandModel::className());
    }
}

