<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\helpers\CommonHelper;
use common\models\mysql\MealModel;
use common\models\mysql\SyncMealModel;
use Yii;
use backend\services\MealService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MealController extends BaseController
{
    public function actionMealList()
    {
        $user = CommonHelper::customer();
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $customerIds = ArrayHelper::getValue($_other, 'customer_id',$user['customer_id']);
        if(!$customerIds) $customerIds = $user['customer_id'];
        if($customerIds) $_other['customer_id'] = explode(',', $customerIds);
        $_order = $this->_sortOrder();
        $data = MealService::getService()->mealList($_page,$_prePage, $_order, $_other);
        return $this->render('meal-list',$data);
    }

    public function actionEditMeal()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $result = MealService::getService()->editMeal($this->paramData);
            $this->log($result);
            if($result == 200)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'meal-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['meal/meal-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = MealModel::find()->where(['id' => $id])
                ->with('brand')
                ->with('color')
                ->with('material')
                ->with('theme')
                ->with('customer')
                ->with('phone')
                ->asArray()->one();

            return $this->render('edit-meal',['model' => $model,]);
        }
    }

    public function actionDeleteMeal()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);
        $ids = ArrayHelper::getValue($this->paramData,'ids');
        $return = MealService::getService()->deleteInfo($ids, MealModel::className());
        $this->log($return);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'meal-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['meal/meal-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionSyncMeal(){
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);
        $ids = ArrayHelper::getValue($this->paramData,'ids');
        $return = MealService::getService()->syncMeal($ids);
        $this->log($return);
        if(is_array($return) and $return['message'])
            return $this->returnAjaxSuccess([
                'message' => $return['message'],
                'navTabId' => 'meal-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['meal/meal-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    // 创建离线同步任务
    public function actionEditTask(){
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = MealService::getService()->editInfo($id, SyncMealModel::className());
            $this->log($result);
            if($result instanceof Model)
                // 生成任务名称
                $customerId = $result->customer_id;
                $taskId =  $result->id;
                $path = \Yii::getAlias('@app/../console');
                $cmd = "cd $path && nohup php yii.php index/sync-meal $customerId $taskId &";
                exec($cmd);
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'task-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['meal/task-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            return $this->render('edit-task');
        }
    }

    // 创建离线同步任务
    public function actionTaskList(){
        $user = CommonHelper::customer();
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $customerIds = ArrayHelper::getValue($_other, 'customer_id',$user['customer_id']);
        if(!$customerIds) $customerIds = $user['customer_id'];
        if($customerIds) $_other['customer_id'] = explode(',', $customerIds);
        $_order = $this->_sortOrder();
        $data = MealService::getService()->taskList($_page,$_prePage, $_order, $_other);
        return $this->render('task-list',$data);
    }
}
