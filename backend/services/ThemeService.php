<?php
namespace backend\services;

use common\models\mysql\ThemeMaterialModel;
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
        $data = ArrayHelper::getValue($data, 'ThemeModel');
        $materialIds = ArrayHelper::getValue($data, 'ThemeMaterialModel.material_id');
        $materialIds = explode(',', $materialIds);
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $model = $this->editInfo($id, ThemeModel::className());
            if(!$model) {
                $transaction->rollBack();
                return false;
            }
            ThemeMaterialModel::deleteAll(['theme_id' => $model->id]);
            $filed = ['theme_id', 'material_id','create_time','update_time'];
            $batchData = [];$now = time();
            foreach ($materialIds as $materialId) {
                $batchData[] = ['theme_id' => $model->id, 'material_id' => $materialId, 'create_time' => $now, 'update_time' => $now];
            }
            \Yii::$app->db->createCommand()->batchInsert(ThemeMaterialModel::tableName(),$filed,$batchData)->execute();
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    public function relationMaterial($ids, $materialIds){
        if(!is_array($ids)) $ids = explode(',', $ids);
        if(!is_array($materialIds)) $materialIds = explode(',', $materialIds);

        $filed = ['theme_id', 'create_time','update_time', 'material_id'];
        $batchData = [];$now = time();
        foreach ($ids as $id){
            $item = ['theme_id' => $id,'create_time' => $now, 'update_time' => $now];
            foreach ($materialIds as $materialId) {
                $item['material_id'] = $materialId;
                $batchData[] = $item;
            }
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            ThemeMaterialModel::deleteAll(['theme_id' => $ids]);
            \Yii::$app->db->createCommand()->batchInsert(ThemeMaterialModel::tableName(),$filed,$batchData)->execute();
            $transaction->commit();
            return true;
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}

