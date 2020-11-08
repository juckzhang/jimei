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
}