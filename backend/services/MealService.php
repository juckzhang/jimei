<?php
namespace backend\services;

use common\models\mysql\MealModel;
use backend\services\base\BackendService;

class MealService extends BackendService
{
    // 机型
    public function mealList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = MealModel::find()
            ->where(['!=','status' , MealModel::STATUS_DELETED])
            ->andFilterWhere(['like','name',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->with('brand')
                ->with('phone')
                ->with('material')
                ->with('color')
                ->with('theme')
                ->offset($offset)
                ->asArray()
                ->all();

        return $data;
    }
}

