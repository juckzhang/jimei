<?php
namespace console\services;

use common\helpers\ExcelHelper;
use common\models\mongodb\CardFirmsModel;
use common\models\mongodb\CardModel;
use common\models\mongodb\CardTypeNameModel;
use common\models\mongodb\ClubModel;
use common\models\mongodb\IssuingDateModel;
use common\models\mongodb\PlayerModel;
use common\models\mongodb\ProductRangeModel;
use common\models\mongodb\TeamModel;
use Yii;
use console\services\base\ConsoleService;

/**
 * Class CreditsService
 * 处理加积分的任务
 * 根据不同的任务生产场景处理积分问题
 * @package common\services
 */
class ImportService extends ConsoleService
{
    /**
     * 文件中倒入数据到数据库
     * @param $file
     */
    public function ImportData($file)
    {
        //获取数据
        $data = $this->_getData($file);
        //下一步操作
        return $this->_batchInsert($data);
    }

    /**
     * 从excel中获取数据
     * @param $file
     * @return array
     */
    private function _getData($file)
    {
        //判断文件是否存在
        $fileName = \Yii::getAlias('@console/data/' . $file);
        if( ! file_exists($fileName))
            exit('file not exists');

        $data = ExcelHelper::readExcel($fileName);
        unset($data[0]);
        return $data;
    }
}