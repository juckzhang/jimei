<?php
namespace backend\controllers;

use Yii;
use backend\services\OrderService;
use yii\helpers\ArrayHelper;

class ApiController extends BaseController
{
    public function beforeAction($action){
        $this->paramData = Yii::$app->getRequest()->postGet();

        return true;
    }

    public function actionListBase(){
        $baseId  = ArrayHelper::getValue($this->paramData,'base_id');
        $data = OrderService::getService()->BaseOrderList($baseId);

        return $this->returnAjaxSuccess($data);
    }
}
