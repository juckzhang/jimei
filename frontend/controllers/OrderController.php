<?php
namespace frontend\controllers;

use common\services\OrderService;
use yii\helpers\ArrayHelper;

class OrderController extends BaseController
{
    //分类接口
    public function actionOrderList()
    {
        $baseId = ArrayHelper::getValue($this->paramData,'base_id');

        $ret = OrderService::getService()->orderList($baseId);

        return $this->returnSuccess($ret);
    }
}
