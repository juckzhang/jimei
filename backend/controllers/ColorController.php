<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\ColorModel;
use Yii;
use backend\services\ColorService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ColorController extends BaseController
{
    public function actionColorList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = ColorService::getService()->MaterialList($_keyWord,$_page,$_prePage);
        return $this->render('color-list',$data);
    }

    public function actionEditColor()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = ColorService::getService()->editColor($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'color-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['color/color-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = ColorModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-color',['model' => $model]);
        }
    }

    public function actionDeleteColor()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = ColorService::getService()->deleteColor($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'color-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['color/color-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
