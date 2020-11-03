<?php
namespace backend\services;

use common\models\mysql\ColorMaterialModel;
use common\models\mysql\MaterialModel;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class MaterialService extends BackendService
{
    public function materialList($page,$prePage,array $order = [],$other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = MaterialModel::find()
            ->where(['!=','status' , MaterialModel::STATUS_DELETED])
            ->andFilterWhere(['like','name',ArrayHelper::getValue($other, 'keyword')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->with('color')
                ->offset($offset)
                ->all();

        return $data;
    }

    public function editMaterial($data){
        $id = ArrayHelper::getValue($data, 'id');
        $model = $this->editInfo($id, MaterialModel::className());

        //获取颜色
        $colorIds = array_unique(explode(',', $model->color_id));

        $filed = ['color_id', 'material_id', 'create_time', 'update_time',];
        $batchData = [];
        $now = time();

        foreach ($colorIds as $colorId){
            $batchData[] = [
                'color_id' => $colorId,
                'material_id' => $model->id,
                'create_time' => $now,
                'update_time' => $now,
            ];
        }

        if($batchData){
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                ColorMaterialModel::deleteAll(['material_id' => $model->id]);
                \Yii::$app->db->createCommand()->batchInsert(ColorMaterialModel::tableName(),$filed,$batchData)->execute();
                $transaction->commit();
            }catch (\Exception $e){
                $transaction->rollBack();
            }
        }

        return $model;
    }
}

