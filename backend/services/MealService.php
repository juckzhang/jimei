<?php
namespace backend\services;

use common\models\mysql\ColorModel;
use common\models\mysql\MealModel;
use backend\services\base\BackendService;
use common\models\mysql\PhoneModel;
use common\models\mysql\ThemeModel;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class MealService extends BackendService
{
    // 机型
    public function mealList($page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = MealModel::find()
            ->where(['!=','status' , MealModel::STATUS_DELETED])
            ->andFilterWhere(['brand_id' => ArrayHelper::getValue($other, 'brand_id')])
            ->andFilterWhere(['mobile_id' => ArrayHelper::getValue($other, 'mobile_id')])
            ->andFilterWhere(['color_id' => ArrayHelper::getValue($other, 'color_id')])
            ->andFilterWhere(['material_id' => ArrayHelper::getValue($other, 'material_id')])
            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['sync_status' => ArrayHelper::getValue($other, 'sync_status')])
            ->andFilterWhere(['theme_id' => ArrayHelper::getValue($other, 'theme_id')]);

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
                ->with('customer')
                ->offset($offset)
                ->asArray()
                ->all();

        return $data;
    }

    // 编辑
    public function editMeal($data){
        $id = ArrayHelper::getValue($data,'id');
        if($id){
            $result = $this->editInfo($id, MealModel::className());
            if($result instanceof Model) return true;

            return false;
        }

        //批量插入
        $phoneIds = ArrayHelper::getValue($data, 'MealModel.mobile_id');
        $colorIds = ArrayHelper::getValue($data, 'MealModel.color_id');
        $themeIds = ArrayHelper::getValue($data, 'MealModel.theme_id');

        if(!$phoneIds or !$colorIds or !$themeIds) return false;
        $phoneIds = explode(',', $phoneIds); $themeIds = explode(',', $themeIds); $colorIds = explode(',', $colorIds);
        $phoneList = PhoneModel::find()->where(['id' => $phoneIds])->with('brand')->asArray()->all();
        $themeList = ThemeModel::find()->where(['id' => $themeIds])->with('customer')->with('material')->asArray()->all();
        $colorList = ColorModel::find()->where(['id' => $colorIds])->asArray()->all();
        $batchData = []; $now = time();
        $filed = ['brand_id','mobile_id','create_time', 'update_time','color_id','customer_id','theme_id','material_id'];
        foreach ($phoneList as $phone){
            $item = ['brand_id' => $phone['brand_id'], 'mobile_id' => $phone['id'],'create_time' => $now,'update_time' => $now];
            foreach ($colorList as $color){
                $item['color_id'] = $color['id'];
                foreach ($themeList as $theme){
                    $item['customer_id'] = $theme['customer_id'];
                    $item['theme_id'] = $theme['id'];
                    $materials = ArrayHelper::getValue($theme, 'material', []);
                    foreach ($materials as $material){
                        $item['material_id'] = $material['material_id'];
                        $batchData[] = $item;
                    }
                }
            }
        }

        $where = ['or'];
        foreach ($batchData as $data){
            $where[] = [
                'brand_id' => $data['brand_id'],
                'mobile_id' => $data['mobile_id'],
                'color_id' => $data['color_id'],
                'customer_id' => $data['customer_id'],
                'theme_id' => $data['theme_id'],
                'material_id' => $data['material_id'],
            ];
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            MealModel::deleteAll($where);
            \Yii::$app->db->createCommand()->batchInsert(MealModel::tableName(),$filed,$batchData)->execute();
            $transaction->commit();

            return true;
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    public function syncMeal($ids){
        $data = MealModel::find()->where(['id' => $ids])
            ->with('brand')
            ->with('phone')
            ->with('material')
            ->with('color')
            ->with('customer')
            ->with('theme')
            ->all();

        $this->updateInfo($ids, MealModel::className(), ['sync_status' => 1]);
        return true;
    }
}

