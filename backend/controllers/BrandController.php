<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\BrandModel;
use Yii;
use backend\services\BrandService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class BrandController extends BaseController
{
    public function actionBrandList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = BrandService::getService()->BrandList($_keyWord,$_page,$_prePage);
        return $this->render('brand-list',$data);
    }

    public function actionEditBrand()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = BrandService::getService()->editBrand($id);
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

        $return = BrandService::getService()->deleteBrand($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'brand-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['brand/brand-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
