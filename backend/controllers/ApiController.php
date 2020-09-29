<?php
namespace backend\controllers;

use backend\services\ColorService;
use backend\services\CustomerService;
use backend\services\MaterialService;
use backend\services\PhoneService;
use backend\services\ThemeService;
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

    //品牌列表
    public function actionBrandList(){
        $page  = ArrayHelper::getValue($this->paramData,'page');
        $count  = ArrayHelper::getValue($this->paramData,'count');
        $data = PhoneService::getService()->BrandList($page, $count);

        return $this->returnAjaxSuccess($data);
    }

    //机型列表
    public function actionPhoneList(){
        $brandId = ArrayHelper::getValue($this->paramData, 'brand_id');
        $page  = ArrayHelper::getValue($this->paramData,'page');
        $count  = ArrayHelper::getValue($this->paramData,'count');
        $data = PhoneService::getService()->PhoneList($page, $count, [], ['brand_id' => $brandId]);

        return $this->returnAjaxSuccess($data);
    }

    //材质列表
    public function actionMaterialList(){
        $page  = ArrayHelper::getValue($this->paramData,'page');
        $count  = ArrayHelper::getValue($this->paramData,'count');
        $data = MaterialService::getService()->materialList(null, $page, $count);

        return $this->returnAjaxSuccess($data);
    }

    //颜色
    public function actionColorList(){
        $page  = ArrayHelper::getValue($this->paramData,'page');
        $count  = ArrayHelper::getValue($this->paramData,'count');
        $data = ColorService::getService()->colorList($page, $count);

        return $this->returnAjaxSuccess($data);
    }

    //客户
    public function actionCustomerList(){
        $page  = ArrayHelper::getValue($this->paramData,'page');
        $count  = ArrayHelper::getValue($this->paramData,'count');
        $data = CustomerService::getService()->CustomerList($page, $count);

        return $this->returnAjaxSuccess($data);
    }

    //素材
    public function actionThemeList(){
        $customterId = ArrayHelper::getValue($this->paramData,'customer_id');
        $updateTime = ArrayHelper::getValue($this->paramData, 'update_time', 0);
        $page  = ArrayHelper::getValue($this->paramData,'page');
        $count  = ArrayHelper::getValue($this->paramData,'count');
        $data = ThemeService::getService()->ThemeList($page, $count, [], ['customer_id' => $customterId,'update_time' => $updateTime]);

        return $this->returnAjaxSuccess($data);
    }

    //机型材质关系数据
    public function actionRelation(){
        $phoneId  = ArrayHelper::getValue($this->paramData,'mobile_id');
        $materialId  = ArrayHelper::getValue($this->paramData,'material_id');
        $data = PhoneService::getService()->relationInfo($phoneId, $materialId);

        return $this->returnAjaxSuccess($data);
    }

    //border url list
    public function actionRelationList(){
        $page  = ArrayHelper::getValue($this->paramData,'page');
        $count  = ArrayHelper::getValue($this->paramData,'count');
        $updateTime = ArrayHelper::getValue($this->paramData, 'update_time', 0);
        $data = PhoneService::getService()->RelationList($page, $count, [], ['update_time' => $updateTime], true);

        return $this->returnAjaxSuccess($data);
    }
}
