<?php
namespace backend\services;

use common\models\mysql\LeftThemeModel;
use common\models\mysql\RightThemeModel;
use common\models\mysql\SideThemeModel;
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
            ->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')])
            ->andFilterWhere([
                'or',
                ['like','name',ArrayHelper::getValue($other, 'keyword')],
                ['like','barcode',ArrayHelper::getValue($other, 'keyword')]
            ])

            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['like','color',ArrayHelper::getValue($other, 'color')])
            ->andFilterWhere(['>=', 'update_time', ArrayHelper::getValue($other, 'update_time')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)
                ->limit($limit)
                ->asArray()
                ->with('customer')
//                ->with('material')
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

    public function sideThemeList($page,$prePage,$order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = SideThemeModel::find()
            ->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')])
            ->andFilterWhere([
                'or',
                ['like','name',ArrayHelper::getValue($other, 'keyword')],
                ['like','barcode',ArrayHelper::getValue($other, 'keyword')]
            ])

            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['like','color',ArrayHelper::getValue($other, 'color')])
            ->andFilterWhere(['>=', 'update_time', ArrayHelper::getValue($other, 'update_time')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)
                ->limit($limit)
                ->asArray()
                ->with('customer')
//                ->with('material')
                ->offset($offset)
                ->all();
            foreach ($models as $key => $model){
                $lborderUrl = $model['left_template_url'];
                if(!empty($borderUrl)) $models[$key]['left_template_url'] = \Yii::$app->params['picUrlPrefix'].$lborderUrl;
                $rborderUrl = $model['right_template_url'];
                if(!empty($rborderUrl)) $models[$key]['right_template_url'] = \Yii::$app->params['picUrlPrefix'].$rborderUrl;
            }
            $data['dataList'] = $models;
        }


        return $data;
    }

    public function leftThemeList($page,$prePage,$order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = LeftThemeModel::find()
            ->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')])
            ->andFilterWhere([
                'or',
                ['like','name',ArrayHelper::getValue($other, 'keyword')],
                ['like','barcode',ArrayHelper::getValue($other, 'keyword')]
            ])

            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['like','color',ArrayHelper::getValue($other, 'color')])
            ->andFilterWhere(['>=', 'update_time', ArrayHelper::getValue($other, 'update_time')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)
                ->limit($limit)
                ->asArray()
                ->with('customer')
//                ->with('material')
                ->offset($offset)
                ->all();
            foreach ($models as $key => $model){
                $lborderUrl = $model['left_template_url'];
                if(!empty($borderUrl)) $models[$key]['left_template_url'] = \Yii::$app->params['picUrlPrefix'].$lborderUrl;
                $rborderUrl = $model['right_template_url'];
                if(!empty($rborderUrl)) $models[$key]['right_template_url'] = \Yii::$app->params['picUrlPrefix'].$rborderUrl;
            }
            $data['dataList'] = $models;
        }


        return $data;
    }

    public function rightThemeList($page,$prePage,$order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = RightThemeModel::find()
            ->andFilterWhere(['status' => ArrayHelper::getValue($other, 'status')])
            ->andFilterWhere([
                'or',
                ['like','name',ArrayHelper::getValue($other, 'keyword')],
                ['like','barcode',ArrayHelper::getValue($other, 'keyword')]
            ])

            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['like','color',ArrayHelper::getValue($other, 'color')])
            ->andFilterWhere(['>=', 'update_time', ArrayHelper::getValue($other, 'update_time')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount']){
            $models = $models->orderBy($order)
                ->limit($limit)
                ->asArray()
                ->with('customer')
//                ->with('material')
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

    public function editSideTheme($data){
        $id = ArrayHelper::getValue($data, 'id');
        $model = $this->editInfo($id, SideThemeModel::className());
        if($model) return true;

        return false;
    }

    public function editLeftTheme($data){
        $id = ArrayHelper::getValue($data, 'id');
        $model = $this->editInfo($id, LeftThemeModel::className());
        if($model) return true;

        return false;
    }

    public function editRightTheme($data){
        $id = ArrayHelper::getValue($data, 'id');
        $model = $this->editInfo($id, RightThemeModel::className());
        if($model) return true;

        return false;
    }

    public function relationMaterial($data){
        $ids = ArrayHelper::getValue($data, 'theme_id', []);
        $materialIds = ArrayHelper::getValue($data, 'material_id', []);
        $customerIds = ArrayHelper::getValue($data, 'customer_id', []);
        if(!is_array($ids)) $ids = array_filter(explode(',', $ids));
        if(!is_array($materialIds)) $materialIds = array_filter(explode(',', $materialIds));
        if(!is_array($customerIds)) $customerIds = array_filter(explode(',', $customerIds));

        if($customerIds){
            $theme = ThemeModel::find()->where(['customer_id' => $customerIds])->asArray()->all();
            $ids = ArrayHelper::merge($ids, ArrayHelper::getColumn($theme, 'id'));
            $ids = array_unique($ids);
        }

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

