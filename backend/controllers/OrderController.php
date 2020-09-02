<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\ColorModel;
use common\models\mysql\DistributionModel;
use common\models\mysql\MaterialModel;
use common\models\mysql\OrderModel;
use common\models\mysql\PhoneModel;
use common\models\mysql\ThemeModel;
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
        $data = OrderService::getService()->OrderList($baseId,$_page,$_prePage);
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
            $model = OrderModel::find()->where(['id' => $id])->asArray()->one();
            $phone = PhoneModel::find()->where(['status' => PhoneModel::STATUS_ACTIVE])->asArray()->all();
            $material = MaterialModel::find()->where(['status' => MaterialModel::STATUS_ACTIVE])->asArray()->all();
            $theme = ThemeModel::find()->where(['status' => ThemeModel::STATUS_ACTIVE])->asArray()->all();
            $color = ColorModel::find()->where(['status' => ColorModel::STATUS_ACTIVE])->asArray()->all();

            return $this->render('edit-order',[
                'model' => $model,
                'phone' => $phone,
                'material' => $material,
                'theme' => $theme,
                'color' => $color,
            ]);
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
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = OrderService::getService()->DistributionList($_keyWord,$_page,$_prePage);
        return $this->render('distribution-list',$data);
    }

    public function actionEditDistribution()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = OrderService::getService()->editInfo($id,DistributionModel::className());
            if($result instanceof Model)
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
}
