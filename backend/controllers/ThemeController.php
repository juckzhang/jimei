<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\CustomerModel;
use common\models\mysql\ThemeModel;
use Yii;
use backend\services\ThemeService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ThemeController extends BaseController
{
    public function actionThemeList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $_order = $this->_sortOrder();
        $data = ThemeService::getService()->ThemeList($_page,$_prePage, $_order, $_other);
        return $this->render('theme-list',$data);
    }

    public function actionEditTheme()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = ThemeService::getService()->editInfo($id,ThemeModel::className());
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'theme-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['theme/theme-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = ThemeModel::find()->where(['id' => $id])
                ->with('customer')
                ->asArray()->one();
            return $this->render('edit-theme',['model' => $model]);
        }
    }

    public function actionDeleteTheme()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = ThemeService::getService()->deleteInfo($ids,ThemeModel::className());
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'theme-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['theme/theme-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
