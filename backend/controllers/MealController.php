<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\BrandModel;
use common\models\mysql\ColorModel;
use common\models\mysql\CustomerModel;
use common\models\mysql\MaterialModel;
use common\models\mysql\MealModel;
use common\models\mysql\PhoneModel;
use common\models\mysql\ThemeModel;
use Yii;
use backend\services\MealService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MealController extends BaseController
{
    public function actionMealList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $_order = $this->_sortOrder();
        $data = MealService::getService()->mealList($_page,$_prePage, $_order, $_other);
        return $this->render('meal-list',$data);
    }

    public function actionEditMeal()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = MealService::getService()->editInfo($id, MealModel::className());
            if($result instanceof Model)
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
}
