<?php
namespace backend\services;

use common\models\mysql\ColorModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class ColorService extends BackendService
{
    public function ColorList($page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = ColorModel::find()
            ->where(['!=','status' , ColorModel::STATUS_DELETED])
            ->andFilterWhere(['like','name',ArrayHelper::getValue($other,'keyword')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }
}

