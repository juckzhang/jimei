<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\BrandModel;
use common\models\mysql\MaterialModel;
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
        return $this->render('phone-list',$data);
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
            $brandList = BrandModel::find()->where(['status' => BrandModel::STATUS_ACTIVE])->asArray()->all();

            return $this->render('edit-phone',['model' => $model, 'brandList' => $brandList]);
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



    public function actionBrandList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = PhoneService::getService()->BrandList($_keyWord,$_page,$_prePage);
        return $this->render('brand-list',$data);
    }

    public function actionEditBrand()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = PhoneService::getService()->editBrand($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'brand-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['brand/brand-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = BrandModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-brand',['model' => $model]);
        }
    }

    public function actionDeleteBrand()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = PhoneService::getService()->deleteBrand($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'brand-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['phone/brand-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionRelationList(){
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $data = PhoneService::getService()->RelationList($_page,$_prePage);
        return $this->render('relation-list',$data);
    }

    public function actionEditBrand()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = PhoneService::getService()->editRelation($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'relation-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['phone/relation-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = BrandModel::find()->where(['id' => $id])->asArray()->one();
            $phoneList = PhoneModel::find()->where(['status' => PhoneModel::STATUS_ACTIVE])->asArray()->all();
            $materialList = MaterialModel::find()->where(['status' => MaterialModel::STATUS_ACTIVE])->asArray()->all();

            return $this->render('edit-relation',[
                'model' => $model,
                'phoneList' => $phoneList,
                'materialList' => $materialList,
            ]);
        }
    }

    public function actionDeleteRelation()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = PhoneService::getService()->deleteRelation($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'relation-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['phone/relation-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
