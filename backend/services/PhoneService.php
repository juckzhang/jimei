<?php
namespace backend\services;

use common\models\mysql\BrandModel;
use common\models\mysql\MaterialPhoneModel;
use common\models\mysql\PhoneModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class PhoneService extends BackendService
{
    // 机型
    public function PhoneList($keyWord,$page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];
        $brandId = ArrayHelper::getValue($other, 'brand_id');

        $models = PhoneModel::find()
            ->where(['!=','status' , PhoneModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord])
            ->andFilterWhere(['brand_id' => $brandId]);

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
    public function RelationList($page,$prePage,array $order = [],$only = false)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = MaterialPhoneModel::find()
            ->where(['!=','status' , MaterialPhoneModel::STATUS_DELETED]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)
                ->limit($limit)
                ->offset($offset);
            if(!$only)
                $models = $models->with('phone')->with('material');
            $models = $models->asArray()->all();
            foreach ($models as $key => $model){
                $borderUrl = $model['border_url'];
                if(!empty($borderUrl)) $models[$key]['border_url'] = \Yii::$app->params['picUrlPrefix'].$borderUrl;
            }
            $data['dataList'] = $models;
        }

        return $data;
    }

    public function relationInfo($phoneId, $materialId){
        $model = MaterialPhoneModel::find()->where(['status' => MaterialPhoneModel::STATUS_ACTIVE])
            ->andWhere(['phone_id' => $phoneId])
            ->andWhere(['material_id' => $materialId])
            ->asArray()
            ->one();
        $borderUrl = $model['border_url'];
        if(!empty($borderUrl)) $model['border_url'] = \Yii::$app->params['picUrlPrefix'].$borderUrl;

        return ['data' => $model];
    }
}

