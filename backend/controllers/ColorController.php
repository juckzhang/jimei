<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\ColorMaterialModel;
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
        $materialId = ArrayHelper::getValue($this->paramData, 'material_id');
        if($materialId) $materialId = array_unique(explode(',', $materialId));
        $_order = $this->_sortOrder();
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $data = ColorService::getService()->ColorList($_page,$_prePage, $_order, $_other);
        $data['colorIds'] = [];
        if($materialId){
            $colors = ColorMaterialModel::find()->select(['color_id'])->where(['material_id' => $materialId])->asArray()->all();
            $data['colorIds'] = ArrayHelper::getColumn($colors, 'color_id', []);
        }

        return $this->render('color-list',$data);
    }

    public function actionEditColor()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = ColorService::getService()->editInfo($id, ColorModel::className());
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

        $return = ColorService::getService()->deleteInfo($ids, ColorModel::className());
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
