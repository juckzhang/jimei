<?php
namespace common\helpers;

class ExcelHelper {
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
}