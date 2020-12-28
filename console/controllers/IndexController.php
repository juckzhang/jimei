<?php
namespace console\controllers;

use backend\services\MealService;
use common\helpers\ExcelHelper;
use common\models\mysql\AdminModel;
use common\models\mysql\ColorModel;
use common\models\mysql\MaterialPhoneModel;
use common\models\mysql\MealModel;
use common\models\mysql\SyncMealModel;
use function Qiniu\setWithoutEmpty;
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

    private static $cellKey = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
        'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
    );

    private static $head = [
        '词','词条位置信息','词条分类', '最近10分钟搜索量','前10分钟搜索量',
        '最近1小时搜索量','前1小时搜索量','最近6小时搜索量','前6小时搜索量',
        '热门微博量','去重后热门微博量', 'f0媒体号热门微博量','f0媒体号去重热门微博量',
        'f0内容号热门微博量','f0内容号去重热门微博量','f0真人号热门微博量','f0真人号去重热门微博量',
        'f1媒体号热门微博量','f1媒体号去重热门微博量',
        'f1内容号热门微博量','f1内容号去重热门微博量','f1真人号热门微博量','f1真人号去重热门微博量',
        'f2媒体号热门微博量','f2媒体号去重热门微博量',
        'f2内容号热门微博量','f2内容号去重热门微博量','f2真人号热门微博量','f2真人号去重热门微博量',
        '观点微博量',
    ];

    public function actionExport(){
        $contents = file_get_contents("http://i.huati.search.weibo.com/band/gather.json");
        $cnt_arr = json_decode($contents, true);
        $file_name = sprintf("data_ten_%s", date('Y-m-d-H-i',$cnt_arr['timeTen']));
        $obj = new \PHPExcel();
        $writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel5');
        $data = $cnt_arr['data'];
        $obj->createSheet();
        $obj->setActiveSheetIndex(0);
        $curSheet = $obj->getActiveSheet();

        foreach (self::$head as $cell => $item){
            $index = sprintf("%s%d",static::$cellKey[$cell], 1);
            $curSheet->setCellValue($index, $item);
        }
        foreach ($data as $key => $item){
            $row = $key + 2;
            $word = isset($item['word']) ? $item['word'] : '';
            $index = sprintf("%s%d",static::$cellKey[0], $row);
            $curSheet->setCellValue($index, $word);

            $bandInfo = isset($item['band_info']) ? $item['band_info'] : '';
            $index = sprintf("%s%d",static::$cellKey[1], $row);
            $curSheet->setCellValue($index, $bandInfo);

            $category = isset($item['category']) ? $item['category'] : '';
            $index = sprintf("%s%d",static::$cellKey[2], $row);
            $curSheet->setCellValue($index, $category);

            $c_1 = isset($item['c_1']) ? $item['c_1'] : '';
            $index = sprintf("%s%d",static::$cellKey[3], $row);
            $curSheet->setCellValue($index, $c_1);

            $p_1 = isset($item['p_1']) ? $item['p_1'] : '';
            $index = sprintf("%s%d",static::$cellKey[4], $row);
            $curSheet->setCellValue($index, $p_1);

            $c_2 = isset($item['c_2']) ? $item['c_2'] : '';
            $index = sprintf("%s%d",static::$cellKey[5], $row);
            $curSheet->setCellValue($index, $c_2);

            $p_2 = isset($item['p_2']) ?$item['p_2']: '';
            $index = sprintf("%s%d",static::$cellKey[6], $row);
            $curSheet->setCellValue($index, $p_2);

            $c_3 = isset($item['c_3']) ?$item['c_3']: '';
            $index = sprintf("%s%d",static::$cellKey[7], $row);
            $curSheet->setCellValue($index, $c_3);

            $p_3 = isset($item['p_3']) ?$item['p_3']: '';
            $index = sprintf("%s%d",static::$cellKey[8], $row);
            $curSheet->setCellValue($index, $p_3);

            $hot_nums = isset($item['hot_nums']) ?$item['hot_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[9], $row);
            $curSheet->setCellValue($index, $hot_nums);

            $hot_nums_unique = isset($item['hot_nums_unique']) ?$item['hot_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[10], $row);
            $curSheet->setCellValue($index, $hot_nums_unique);

            // f0
            $hot_f0_media_nums = isset($item['hot_f0_media_nums']) ?$item['hot_f0_media_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[11], $row);
            $curSheet->setCellValue($index, $hot_f0_media_nums);

            $hot_f0_media_nums_unique = isset($item['hot_f0_media_nums_unique']) ?$item['hot_f0_media_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[12], $row);
            $curSheet->setCellValue($index, $hot_f0_media_nums_unique);

            $hot_f0_content_nums = isset($item['hot_f0_content_nums']) ?$item['hot_f0_content_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[13], $row);
            $curSheet->setCellValue($index, $hot_f0_content_nums);

            $hot_f0_content_nums_unique = isset($item['hot_f0_content_nums_unique']) ?$item['hot_f0_content_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[14], $row);
            $curSheet->setCellValue($index, $hot_f0_content_nums_unique);

            $hot_f0_person_nums = isset($item['hot_f0_person_nums']) ?$item['hot_f0_person_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[15], $row);
            $curSheet->setCellValue($index, $hot_f0_person_nums);

            $hot_f0_person_nums_unique = isset($item['hot_f0_person_nums_unique']) ?$item['hot_f0_person_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[16], $row);
            $curSheet->setCellValue($index, $hot_f0_person_nums_unique);

            //f1
            $hot_f1_media_nums = isset($item['hot_f1_media_nums']) ?$item['hot_f1_media_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[17], $row);
            $curSheet->setCellValue($index, $hot_f1_media_nums);

            $hot_f1_media_nums_unique = isset($item['hot_f1_media_nums_unique']) ?$item['hot_f1_media_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[18], $row);
            $curSheet->setCellValue($index, $hot_f1_media_nums_unique);

            $hot_f1_content_nums = isset($item['hot_f1_content_nums']) ?$item['hot_f1_content_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[19], $row);
            $curSheet->setCellValue($index, $hot_f1_content_nums);

            $hot_f1_content_nums_unique = isset($item['hot_f1_content_nums_unique']) ?$item['hot_f1_content_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[20], $row);
            $curSheet->setCellValue($index, $hot_f1_content_nums_unique);

            $hot_f1_person_nums = isset($item['hot_f1_person_nums']) ?$item['hot_f1_person_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[21], $row);
            $curSheet->setCellValue($index, $hot_f1_person_nums);

            $hot_f1_person_nums_unique = isset($item['hot_f1_person_nums_unique']) ?$item['hot_f1_person_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[22], $row);
            $curSheet->setCellValue($index, $hot_f1_person_nums_unique);

            //f2
            $hot_f2_media_nums = isset($item['hot_f2_media_nums']) ?$item['hot_f2_media_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[17], $row);
            $curSheet->setCellValue($index, $hot_f2_media_nums);

            $hot_f2_media_nums_unique = isset($item['hot_f2_media_nums_unique']) ?$item['hot_f2_media_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[18], $row);
            $curSheet->setCellValue($index, $hot_f2_media_nums_unique);

            $hot_f2_content_nums = isset($item['hot_f2_content_nums']) ?$item['hot_f2_content_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[19], $row);
            $curSheet->setCellValue($index, $hot_f2_content_nums);

            $hot_f2_content_nums_unique = isset($item['hot_f2_content_nums_unique']) ?$item['hot_f2_content_nums_unique']: '';
            $index = sprintf("%s%d",static::$cellKey[20], $row);
            $curSheet->setCellValue($index, $hot_f2_content_nums_unique);

            $hot_f2_person_nums = isset($item['hot_f2_person_nums']) ? $item['hot_f2_person_nums']: '';
            $index = sprintf("%s%d",static::$cellKey[21], $row);
            $curSheet->setCellValue($index, $hot_f2_person_nums);

            $hot_f2_person_nums_unique = isset($item['hot_f2_person_nums_unique']) ? $item['hot_f2_person_nums_unique'] : '';
            $index = sprintf("%s%d",static::$cellKey[22], $row);
            $curSheet->setCellValue($index, $hot_f2_person_nums_unique);
        }

        $file = iconv('utf-8', 'gb2312', $file_name);
        $writer->save($file.'.xls');
        return;
    }
}