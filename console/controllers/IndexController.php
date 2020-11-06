<?php
namespace console\controllers;

use backend\services\MealService;
use common\helpers\ExcelHelper;
use common\models\mysql\AdminModel;
use common\models\mysql\ColorModel;
use common\models\mysql\MaterialPhoneModel;
use common\models\mysql\MealModel;
use common\models\mysql\SyncMealModel;
use yii\helpers\ArrayHelper;

class IndexController extends BaseController{
    // 注册用户
    public function actionRegister($username, $password)
    {
        $username = 'admin';
        $password = 'admin123';
        $model = new AdminModel();
        $password = \Yii::$app->security->generatePasswordHash($password);
        $model->add(['username' => $username, 'password' => $password]);
    }

    // 导出商品信息
    public function actionExportGoods($fileName){
        $relationList = MaterialPhoneModel::find()
            ->with('phone')
            ->with('material')
            ->asArray()
            ->all();
        $colorList = ColorModel::find()->select(['barcode','name'])->asArray()->all();
        $data[] = ['商品名称', '商品编码'];

        foreach ($relationList as $relation){
            foreach ($colorList as $color){
                $data[] = [
                    sprintf(
                        "%s%s%s (%s)",
                        $relation['phone']['brand']['name'],
                        $relation['phone']['modal'],
                        $relation['material']['name'],
                        $color['name']
                    ),
                    sprintf(
                        "%s%s%s%s",
                        $relation['phone']['brand']['barcode'],
                        $relation['phone']['barcode'],
                        $relation['material']['barcode'],
                        $color['barcode']
                    )
                ];
            }
        }

        ExcelHelper::writeExcel($fileName, $data);
    }

    public function actionSyncMeal($customerId, $taskId = 0){
        $id = 0;
        try {
            while (true){
                $mealList = MealModel::find()->select(['id'])
                    ->where(['>', 'id', $id])
                    ->andwhere(['customer_id' => $customerId, 'sync_status' => 0])
                    ->asArray()
                    ->limit(100)
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                $ids = ArrayHelper::getColumn($mealList, 'id');
                $cnt = count($ids);
                if($cnt > 0){
                    $res = MealService::getService()->syncMeal($ids);
                    \Yii::$app->bizLog->log(['ids' => $ids, 'result' => $res], 'req', 'Info');
                    sleep(1);
                    $id = $ids[$cnt - 1];
                }

                if($cnt < 100){
                    if($taskId) {
                        SyncMealModel::updateAll(['sync_status' => 1], ['id' => $taskId]);
                    }
                    break;
                }
            }
        }catch (\Exception $e){
            if($taskId) {
                SyncMealModel::updateAll(['sync_status' => 1], ['id' => $taskId]);
            }
        }
    }
}