<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\helpers\CommonHelper;
use common\models\mysql\MealModel;
use Yii;
use backend\services\MealService;
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
        $_other['customer_id'] = ArrayHelper::getValue($_other,'customer_id', $user['customer_id']);
        $_order = $this->_sortOrder();
        $data = MealService::getService()->mealList($_page,$_prePage, $_order, $_other);
        return $this->render('meal-list',$data);
    }

    public function actionEditMeal()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $result = MealService::getService()->editMeal($this->paramData);
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
        if(is_array($return) and $return['message'])
            return $this->returnAjaxSuccess([
                'message' => $return['message'],
                'navTabId' => 'meal-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['meal/meal-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
