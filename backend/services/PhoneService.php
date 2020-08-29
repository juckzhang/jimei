<?php
namespace backend\services;

use common\models\mysql\BrandModel;
use common\models\mysql\MaterialPhoneModel;
use common\models\mysql\PhoneModel;
use backend\services\base\BackendService;

class PhoneService extends BackendService
{
    // 机型
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

    //品牌
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
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function allList(){
        return BrandModel::find()->where(['!=','status' , BrandModel::STATUS_DELETED])->asArray()->all();
    }

    // 机型
    public function RelationList($page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = PhoneModel::find()
            ->where(['!=','status' , PhoneModel::STATUS_DELETED]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->offset($offset)
                ->with('phone')
                ->with('material')
                ->asArray()
                ->all();

        return $data;
    }
}

