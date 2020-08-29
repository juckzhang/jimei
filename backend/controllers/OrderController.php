<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\OrderModel;
use Yii;
use backend\services\OrderService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class OrderController extends BaseController
{
    public function actionOrderList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = OrderService::getService()->OrderlList($_keyWord,$_page,$_prePage);
        return $this->render('order-list',$data);
    }

    public function actionEditOrder()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = OrderService::getService()->editOrder($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'order-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['order/order-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = OrderModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-order',['model' => $model]);
        }
    }

    public function actionDeleteOrder()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = OrderService::getService()->deleteOrder($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'order-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['order/order-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
