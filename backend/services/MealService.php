<?php
namespace backend\services;

use common\constants\CodeConstant;
use common\helpers\ClientHelper;
use common\models\mysql\ColorModel;
use common\models\mysql\MaterialModel;
use common\models\mysql\MealModel;
use backend\services\base\BackendService;
use common\models\mysql\PhoneModel;
use common\models\mysql\SyncMealModel;
use common\models\mysql\ThemeModel;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class MealService extends BackendService
{
    public function taskList($page,$prePage,array $order = [], $other = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = SyncMealModel::find()
            ->andFilterWhere(['customer_id' => ArrayHelper::getValue($other, 'customer_id')])
            ->andFilterWhere(['sync_status' => ArrayHelper::getValue($other, 'sync_status')]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)
                ->limit($limit)
                ->with('customer')
                ->offset($offset)
                ->asArray()
                ->all();

        return $data;
    }

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
        $brandIds = ArrayHelper::getValue($data, 'MealModel.brand_id');
        $phoneIds = ArrayHelper::getValue($data, 'MealModel.mobile_id');
        $materialIds = ArrayHelper::getValue($data, 'MealModel.material_id');
        $colorIds = ArrayHelper::getValue($data, 'MealModel.color_id');
        $customerIds = ArrayHelper::getValue($data, 'MealModel.customer_id');
        $themeIds = ArrayHelper::getValue($data, 'MealModel.theme_id');

        if((!$phoneIds and !$brandIds) or !$colorIds or !$materialIds or (!$themeIds and !$customerIds)) return false;
        $brandIds = array_filter(array_unique(explode(',', $brandIds)));
        $phoneIds = array_filter(array_unique(explode(',', $phoneIds)));
        $materialIds = array_filter(array_unique(explode(',', $materialIds)));
        $colorIds = array_filter(array_unique(explode(',', $colorIds)));
        $customerIds = array_filter(array_unique(explode(',', $customerIds)));
        $themeIds = array_filter(array_unique(explode(',', $themeIds)));
        $phoneWhere = ['id' => $phoneIds];
        if(empty($phoneIds)) $phoneWhere = ['brand_id' => $brandIds];
        $themeWhere = ['id' => $themeIds];
        if(empty($themeIds)) $themeWhere = ['customer_id' => $customerIds];
        $phoneList = PhoneModel::find()->where($phoneWhere)->with('brand')->asArray()->all();
        $phoneList = ArrayHelper::index($phoneList, 'id');
        $themeList = ThemeModel::find()->where($themeWhere)->with('customer')->asArray()->all();
        $colorList = ColorModel::find()->where(['id' => $colorIds])->asArray()->all();
        $colorList = ArrayHelper::index($colorList, 'id');
        $materialList = MaterialModel::find()->where(['id' => $materialIds])->with('phone')->with('color')->asArray()->all();
        $batchData = []; $now = time();
        $filed = ['brand_id','mobile_id','create_time', 'update_time','color_id','customer_id','theme_id','material_id'];

        foreach ($materialList as $material){
            $phones = ArrayHelper::getValue($material, 'phone', []);
            $colors = ArrayHelper::getValue($material, 'color', []);
            foreach ($phones as $phone){
                if(isset($phoneList[$phone['mobile_id']])){
                    $phone = $phoneList[$phone['mobile_id']];
                    foreach ($colors as $color){
                        if(isset($colorList[$color['color_id']])){
                            $color = $colorList[$color['color_id']];
                            foreach ($themeList as $theme){
                                $batchData[] = [
                                    'brand_id' => $phone['brand_id'],
                                    'mobile_id' => $phone['id'],
                                    'create_time' => $now,
                                    'update_time' => $now,
                                    'color_id' => $color['id'],
                                    'customer_id' => $theme['customer_id'],
                                    'theme_id' => $theme['id'],
                                    'material_id' => $material['id'],
                                ];
                            }
                        }
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

        $ret = CodeConstant::NO_MEAL_RESULT;
        $starttime = microtime(true);
        $errorMessage = '';
        if($batchData){
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                MealModel::deleteAll($where);
                \Yii::$app->db->createCommand()->batchInsert(MealModel::tableName(),$filed,$batchData)->execute();
                $transaction->commit();

                $ret = 200;
            }catch (\Exception $e){
                $transaction->rollBack();
                $ret = CodeConstant::EDIT_MEAL_FAILED;
                $errorMessage = $e->getMessage();
            }
        }
        $endtime = microtime(true);
        \Yii::$app->bizLog->log([
            'batchData' => $batchData,
            'total' => count($batchData),
            'time' => sprintf("%.3f" ,($endtime - $starttime)) ,
            'errorMessage' => $errorMessage,
        ], 'mysql', 'Info');

        return $ret;
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
        $param = $meal = [];
        foreach ($data as $item){
            if(!$item['brand'] or !$item['phone'] or !$item['material'] or !$item['color']
                or !$item['customer'] or !$item['theme']
            ) continue;
            $suitecode = sprintf(
                "%s%s%s%s%s%s",
                $item['brand']['barcode'],
                $item['phone']['barcode'],
                $item['material']['barcode'],
                $item['color']['barcode'],
                $item['customer']['barcode'],
                $item['theme']['barcode']
            );
            $meal[$suitecode] = $item['id'];
            $param[] = [
                'suitecode' => $suitecode,
                'suitename' => sprintf(
                    "%s%s%s (%s) %s",
                    $item['brand']['name'],
                    $item['phone']['modal'],
                    $item['material']['name'],
                    $item['color']['name'],
                    $item['theme']['name']
                ),
                'printtype' => 0,
                'remark' => '',
                'unit' => '个',
                'suiteitemdetail' => [
                    [
                        'goodscode' => sprintf(
                            "%s%s%s%s",
                            $item['brand']['barcode'],
                            $item['phone']['barcode'],
                            $item['material']['barcode'],
                            $item['color']['barcode']
                        ),
                        'qty' => 1,
                        'price' => 0,
                    ],
                ],
            ];
        }
        if(!$meal) return ['code' => -1,'message' => '无有效的套餐信息!'];
        $res = ClientHelper::rsyncMeal(['suiteitems' => $param]);
        //查出同步失败的套餐
        foreach ($res['mealCode'] as $code){
            if(isset($meal[$code])) unset($meal[$code]);
        }
        if($res['code'] !== 0 and !$res['mealCode']) $meal = [];
        if($meal){
            $ids = array_values($meal);
            $this->updateInfo($ids, MealModel::className(), ['sync_status' => 1]);
        }

        return $res;
    }
}

