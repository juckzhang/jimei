<?php
namespace backend\services;

use common\models\mysql\ColorModel;
use backend\services\base\BackendService;

class ColorService extends BackendService
{
    public function ColorList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = ColorModel::find()
            ->where(['!=','status' , ColorModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function editColor($id)
    {
        return $this->editInfo($id,ColorModel::className());
    }

    public function deleteColor($id)
    {
        return $this->deleteInfo($id,ColorModel::className());
    }
}

