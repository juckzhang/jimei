<?php
namespace console\services;

use common\models\mysql\MealModel;
use common\models\mysql\SyncMealModel;
use console\services\base\ConsoleService;
use yii\helpers\ArrayHelper;

class MealService extends ConsoleService
{
    public function syncMeal($customerId, $taskId = 0){
        $id = 0;
        $counts = 0;
        $this->updateTask(['sync_status' => 3], $taskId);
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
                $counts += $cnt;
                if($cnt > 0){
                    $res = \backend\services\MealService::getService()->syncMeal($ids);
                    \Yii::$app->bizLog->log(['ids' => $ids, 'taskId' => $taskId,'result' => $res], 'req', 'Info');
                    $id = $ids[$cnt - 1];
                    if(isset($res['message'])) {
                        $message[] = $res['message'];
                    }

                    $this->updateTask(['result' => "已处理 {$counts} 个套餐"], $taskId);
                }
                if($cnt < 100){
                    break;
                }
            }
        }catch (\Exception $e){
            $message[] = '同步失败!';
        }

        $message = implode('|',array_unique($message));
        $this->updateTask(['sync_status' => 1,'result' => $message], $taskId);
    }

    // 更新任务信息
    private function updateTask($column, $taskId){
        if($taskId <= 0) return ;

        \Yii::$app->bizLog->log([
            'column' => $column,
            'taskId' => $taskId,
        ], 'req', 'Info');
        SyncMealModel::updateAll($column, ['id' => $taskId]);
    }
}