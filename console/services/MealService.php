<?php
namespace console\services;

use common\models\mysql\MealModel;
use common\models\mysql\SyncMealModel;
use console\services\base\ConsoleService;
use yii\helpers\ArrayHelper;

/**
 * Class CreditsService
 * 处理加积分的任务
 * 根据不同的任务生产场景处理积分问题
 * @package common\services
 */
class MealService extends ConsoleService
{
    public function syncMeal($customerId, $taskId = 0){
        $id = 0;
        $message = [];
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
                    $res = \backend\services\MealService::getService()->syncMeal($ids);
                    \Yii::$app->bizLog->log(['ids' => $ids, 'result' => $res], 'req', 'Info');
                    $id = $ids[$cnt - 1];
                    if(isset($res['message'])) {
                        $message[] = $res['message'];
                    }
                    sleep(1);
                }

                if($cnt < 100){
                    break;
                }
            }
        }catch (\Exception $e){
            if($taskId) {
                $message[] = '同步失败!';
            }
        }

        if($taskId) {
            $message = implode('|',array_unique($message));
            SyncMealModel::updateAll(['sync_status' => 1,'result' => $message], ['id' => $taskId]);
        }
    }
}