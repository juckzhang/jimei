<?php
namespace backend\controllers;

use backend\services\DistributionService;
use Yii;
use backend\services\OrderService;
use yii\helpers\ArrayHelper;

class ApiController extends BaseController
{
    public function beforeAction($action){
        $this->paramData = Yii::$app->getRequest()->postGet();
    }

    public function actionListBase(){
        $baseId  = ArrayHelper::getValue($this->paramData,'base_id');
        $data = OrderService::getService()->BaseOrderList($baseId);

        return $this->returnAjaxSuccess($data);
    }
}
