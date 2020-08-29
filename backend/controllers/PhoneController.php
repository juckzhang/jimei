<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\PhoneModel;
use Yii;
use backend\services\PhoneService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class PhoneController extends BaseController
{
    public function actionPhoneList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = PhoneService::getService()->PhoneList($_keyWord,$_page,$_prePage);
        return $this->render('Phone-list',$data);
    }

    public function actionEditPhone()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = PhoneService::getService()->editPhone($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'phone-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['phone/phone-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = PhoneModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-phone',['model' => $model]);
        }
    }

    public function actionDeletePhone()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = PhoneService::getService()->deletePhone($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'phone-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['phone/phone-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
