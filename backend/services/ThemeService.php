<?php
namespace backend\services;

use common\models\mysql\MaterialModel;
use common\models\mysql\ThemeModel;
use backend\services\base\BackendService;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ThemeService extends BackendService
{
    public function ThemeList($page,$prePage,$order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = ThemeModel::find()
            ->where(['!=','status' , ThemeModel::STATUS_DELETED])
            ->andFilterWhere(['like','name',ArrayHelper::getValue($other, 'keyword')])
            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['material_id' => ArrayHelper::getValue($other, 'material_id')])
            ->andFilterWhere(['>=', 'update_time', ArrayHelper::getValue($other, 'update_time')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)
                ->limit($limit)
                ->asArray()
                ->with('customer')
                ->with('material')
                ->offset($offset)
                ->all();
            foreach ($models as $key => $model){
                $borderUrl = $model['template_url'];
                if(!empty($borderUrl)) $models[$key]['template_url'] = \Yii::$app->params['picUrlPrefix'].$borderUrl;
            }
            $data['dataList'] = $models;
        }


        return $data;
    }

    public function editTheme($data){
        $id = ArrayHelper::getValue($data, 'id');
        if($id){
            $result = $this->editInfo($id, MaterialModel::className());
            if($result instanceof Model) return true;

            return false;
        }
        $materialIds = ArrayHelper::getValue($data, 'ThemeModel.material');
        $data = ArrayHelper::getValue($data, 'ThemeModel');
        $materialIds = explode(',', $materialIds);
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            foreach ($materialIds as $materialId){
                $data['material_id'] = $materialId;
                $model = new ThemeModel();
                $model->load($data, '');
                $model->save();
            }
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }


        return true;
    }

    public function relationMaterialrelationMaterial($ids, $materialId){
        if(!is_array($ids)) $ids = explode(',', $ids);
        return $this->updateInfo($ids, ThemeModel::className(), ['material_id' => $materialId]);
    }
}

