<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\DistributionModel;
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
        $baseId  = ArrayHelper::getValue($this->paramData,'base_id');
        $_other = ArrayHelper::getValue($this->paramData, 'other');
        $_order = $this->_sortOrder();
        $data = OrderService::getService()->OrderList($baseId,$_page,$_prePage, $_order, $_other);
        return $this->render('order-list',$data);
    }

    public function actionEditOrder()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = OrderService::getService()->editInfo($id,OrderModel::className());
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
            $model = OrderModel::find()->where(['id' => $id])
                ->with('brand')
                ->with('phone')
                ->with('material')
                ->with('color')
                ->with('customer')
                ->with('theme')
                ->asArray()->one();
            return $this->render('edit-order',['model' => $model,]);
        }
    }

    public function actionDeleteOrder()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = OrderService::getService()->deleteInfo($ids,OrderModel::className());
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'order-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['order/order-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionDistributionList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_order  = $this->_sortOrder();
        $_other  = ArrayHelper::getValue($this->paramData, 'other');
        $data = OrderService::getService()->DistributionList($_page,$_prePage,$_order, $_other);
        return $this->render('distribution-list',$data);
    }

    public function actionEditDistribution()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $result = OrderService::getService()->editDistribution($this->paramData);
            if($result == 200)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'distribution-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['order/distribution-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = DistributionModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-distribution',['model' => $model]);
        }
    }

    public function actionDeleteDistribution()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = OrderService::getService()->deleteInfo($ids, DistributionModel::className());
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'distribution-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['order/distribution-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionParseOrder(){
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = OrderService::getService()->reparseOrder($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '更新成功',
                'navTabId' => 'order-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['order/order-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
