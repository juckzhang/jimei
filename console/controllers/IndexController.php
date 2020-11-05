<?php
namespace console\controllers;

use backend\services\MealService;
use common\helpers\ExcelHelper;
use common\models\mysql\AdminModel;
use common\models\mysql\ColorModel;
use common\models\mysql\MaterialPhoneModel;
use common\models\mysql\MealModel;
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

    public function actionSyncMeal(){
        $offset = 0;
        while (true){
            $mealList = MealModel::find()->select(['id'])
                ->where(['customer_id' => [4,16,21,6]])
                ->offset($offset)
                ->asArray()
                ->limit(100)
                ->orderBy(['id' => SORT_ASC])
                ->all();
            $ids = ArrayHelper::getColumn($mealList, 'id');
            if(count($ids) > 0){
                $offset += 100;
                $res = MealService::getService()->syncMeal($ids);
                file_put_contents('/mnt/data/openresty/htdocs/jimei/backend/runtime/logs/rsync_meal.log',json_encode($res).PHP_EOL, FILE_APPEND);
            }

            if(count($ids) < 100)
                break;
        }
    }
}