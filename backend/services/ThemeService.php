<?php
namespace backend\services;

use common\models\mysql\ThemeModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class ThemeService extends BackendService
{
    public function ThemeList($page,$prePage,$order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = ThemeModel::find()
            ->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')])
            ->andFilterWhere([
                'or',
                ['like','name',ArrayHelper::getValue($other, 'keyword')],
                ['like','barcode',ArrayHelper::getValue($other, 'keyword')]
            ])

            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['like','color',ArrayHelper::getValue($other, 'color')])
            ->andFilterWhere(['like','type',ArrayHelper::getValue($other, 'type')])
            ->andFilterWhere(['>=', 'update_time', ArrayHelper::getValue($other, 'update_time')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)
                ->limit($limit)
                ->asArray()
                ->with('customer')
                ->offset($offset)
                ->all();
            foreach ($models as $key => $model){
                $models[$key]['template_url'] = $this->handlerPic($model, 'template_url');
                $models[$key]['left_template_url'] = $this->handlerPic($model, 'left_template_url');
                $models[$key]['right_template_url'] = $this->handlerPic($model, 'right_template_url');
            }
            $data['dataList'] = $models;
        }

        return $data;
    }
}

