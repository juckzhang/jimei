<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\CustomerModel;
use Yii;
use backend\services\CustomerService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class CustomerController extends BaseController
{
    public function actionCustomerList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = CustomerService::getService()->CustomerList($_keyWord,$_page,$_prePage);
        return $this->render('customer-list',$data);
    }

    public function actionEditCustomer()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = CustomerService::getService()->editInfo($id, CustomerModel::className());
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'customer-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['customer/customer-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = CustomerModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-customer',['model' => $model]);
        }
    }

    public function actionDeleteCustomer()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = CustomerService::getService()->deleteInfo($ids, CustomerModel::className());
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'customer-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['customer/customer-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
