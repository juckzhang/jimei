<?php
namespace backend\services;

use common\models\mysql\ThemeModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class ThemeService extends BackendService
{
    public function ThemeList($keyWord,$page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];
        $customerId = ArrayHelper::getValue($other, 'customer_id');

        $models = $cardModels = ThemeModel::find()
            ->where(['!=','status' , ThemeModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord])
            ->andFilterWhere(['customer_id' => $customerId]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)->limit($limit)->asArray()->with('customer')->offset($offset)->all();
            foreach ($models as $key => $model){
                $borderUrl = $model['template_url'];
                if(!empty($borderUrl)) $models[$key]['template_url'] = \Yii::$app->params['picUrlPrefix'].$borderUrl;
            }
            $data['dataList'] = $models;
        }


        return $data;
    }
}

