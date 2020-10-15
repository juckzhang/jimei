<?php
namespace common\helpers;

class ExcelHelper {
    private static $cellKey = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
        'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
    );

    public static function readExcel($fileName)
    {
        $_IOFactory = \PHPExcel_IOFactory::load($fileName);
        $sheet = $_IOFactory->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数

        /** 循环读取每个单元格的数据 */
        for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
            for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
                $dataset[$row - 1][] = $sheet->getCell($column.$row)->getFormattedValue();
            }
        }
        return array_filter($dataset,function($item){
            return trim( is_array($item) && ! empty($item[0]) &&$item[0]);
        });
    }

    public static function writeExcel($file, $data, $isBrowser = false){
        $obj = new \PHPExcel();
        $writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
        $obj->createSheet();
        $obj->setActiveSheetIndex(0);
        $curSheet = $obj->getActiveSheet();

        foreach ($data as $row => $item){
            foreach ($item as $cell => $value){
                $index = sprintf("%s%d",static::$cellKey[$cell], $row);
                $curSheet->setCellValue($index, $value);
            }
        }

        $file = iconv('utf-8', 'gb2312', $file);
        if(! $isBrowser){
            $writer->save($file.'.xlsx');
            return;
        }

        ob_end_clean();
        header('Content-Type: application/vnd.ms-execl;charset=utf-8;name="'.$file.'xlsx"');
        header('Content-Disposition: attachment;filename="'.$file.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}