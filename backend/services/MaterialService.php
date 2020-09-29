<?php
namespace backend\services;

use common\models\mysql\MaterialModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class MaterialService extends BackendService
{
    public function materialList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = MaterialModel::find()
            ->where(['!=','status' , MaterialModel::STATUS_DELETED])
            ->andFilterWhere(['like','name',ArrayHelper::getValue($other, 'keyword')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }
}

