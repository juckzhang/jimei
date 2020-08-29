<?php
namespace backend\controllers;

use common\constants\CodeConstant;
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
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = ThemeService::getService()->ThemeList($_keyWord,$_page,$_prePage);
        return $this->render('theme-list',$data);
    }

    public function actionEditTheme()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = ThemeService::getService()->editTheme($id);
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
            $model = ThemeModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-theme',['model' => $model]);
        }
    }

    public function actionDeleteTheme()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = ThemeService::getService()->deleteTheme($ids);
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
