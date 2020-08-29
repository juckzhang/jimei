<?php
namespace console\controllers;

use common\models\mysql\AdminModel;

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

    public function actionParse()
    {
        $lines = explode("\n", file_get_contents(\Yii::getAlias('@runtime/zimu.srt')));
        foreach ($lines as $key => $value){
            $index = $key % 5;
            if(!$index and !trim($value)) break;

            switch ($index){
                case 0: $item['line_number'] = trim($value); break;
                case 1:
                    $time = explode('-->', $value);
                    $item['start_time'] = trim($time[0]);
                    $item['end_time'] = trim($time[1]);
                    break;
                case 2: //处理中文
                case 3: //处理其他语言
                    $item['lang_type'] = $index == 2 ? 'zh_CN' : 'en_US';
                    $item['content'] = trim($value);
                    var_dump($item);
                    break;
                default:
                    break;
            }
        }
    }
}