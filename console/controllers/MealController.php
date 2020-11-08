<?php
namespace console\controllers;


use common\models\mysql\SyncMealModel;
use console\services\MealService;

class MealController extends BaseController{

    public function actionRun(){
        $taskList = SyncMealModel::find()->where(['sync_status' => [0, 3]])
            ->all();
        $service = MealService::getService();
        foreach ($taskList as $task){
            $service->syncMeal($task['customer_id'], $task['id']);
        }
    }

    public function actionSyncMeal($customerId, $taskId = 0){
        MealService::getService()->syncMeal($customerId, $taskId);
    }
}