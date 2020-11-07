<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\MaterialModel;
use Yii;
use backend\services\MaterialService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class MaterialController extends BaseController
{
    public function actionMaterialList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $_order = $this->_sortOrder();
        $data = MaterialService::getService()->MaterialList($_page,$_prePage, $_order, $_other);
        return $this->render('material-list',$data);
    }

    public function actionEditMaterial()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = MaterialService::getService()->editMaterial($this->paramData);
            $this->log($result);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'material-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['material/material-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = MaterialModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-material',['model' => $model]);
        }
    }

    public function actionDeleteMaterial()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);
        $ids = ArrayHelper::getValue($this->paramData,'ids');
        $return = MaterialService::getService()->deleteInfo($ids,MaterialModel::className());
        $this->log($return);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'material-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['material/material-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
