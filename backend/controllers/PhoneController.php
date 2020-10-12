<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\BrandModel;
use common\models\mysql\MaterialPhoneModel;
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
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $_order = $this->_sortOrder();
        $data = PhoneService::getService()->PhoneList($_page,$_prePage,$_order, $_other);
        $data['brandList'] = BrandModel::find()->asArray()->all();
        return $this->render('phone-list',$data);
    }

    public function actionEditPhone()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = PhoneService::getService()->editInfo($id, PhoneModel::className());
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
            $model = PhoneModel::find()->where(['id' => $id])
                ->with('brand')
                ->asArray()->one();

            return $this->render('edit-phone',['model' => $model]);
        }
    }

    public function actionDeletePhone()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = PhoneService::getService()->deleteInfo($ids, PhoneModel::className());
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
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $brandIds = ArrayHelper::getValue($_other, 'brand_id');
        if($brandIds) $_other['brand_id'] = explode(',', $brandIds);
        $_order = $this->_sortOrder();
        $data = PhoneService::getService()->BrandList($_page,$_prePage, $_order, $_other);
        return $this->render('brand-list',$data);
    }

    public function actionEditBrand()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = PhoneService::getService()->editInfo($id, BrandModel::className());
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'brand-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['phone/brand-list'])
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

        $return = PhoneService::getService()->deleteInfo($ids, BrandModel::className());
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
        $_order = $this->_sortOrder();
        $_other = ArrayHelper::getValue($this->paramData, 'other');
        $data = PhoneService::getService()->RelationList($_page,$_prePage, $_order, $_other);
        return $this->render('relation-list',$data);
    }

    public function actionEditRelation()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = PhoneService::getService()->editInfo($id, MaterialPhoneModel::className());
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
            $model = MaterialPhoneModel::find()
                ->where(['id' => $id])
                ->with('phone')
                ->with('material')
                ->asArray()->one();

            return $this->render('edit-relation',['model' => $model]);
        }
    }

    public function actionDeleteRelation()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = PhoneService::getService()->deleteInfo($ids, MaterialPhoneModel::className());
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
