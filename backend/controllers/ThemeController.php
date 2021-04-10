<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\helpers\CommonHelper;
use common\models\mysql\ColorModel;
use common\models\mysql\LeftThemeModel;
use common\models\mysql\RightThemeModel;
use common\models\mysql\SideThemeModel;
use common\models\mysql\ThemeModel;
use Yii;
use backend\services\ThemeService;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ThemeController extends BaseController
{
    public function actionThemeList()
    {
        $user = CommonHelper::customer();
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_other  = ArrayHelper::getValue($this->paramData,'other');
        $customerIds = ArrayHelper::getValue($_other, 'customer_id',$user['customer_id']);
        if($customerIds) $_other['customer_id'] = explode(',', $customerIds);
        $_order = $this->_sortOrder();

        $data = ThemeService::getService()->ThemeList($_page,$_prePage, $_order, $_other);
        $data['colorList'] = ColorModel::find()->asArray()->all();

        return $this->render('theme-list',$data);
    }

    public function actionThemeCheck(){
        $customerId = ArrayHelper::getValue($this->paramData,'ck.customer_id');
        $name = ArrayHelper::getValue($this->paramData,'ch.pageNum');

        $dataList = ThemeModel::find()->where(['customer_id' => $customerId])
            ->andWhere(['like','name', $name])->all();

        $ret = ['like' => false, 'eq' => 0];
        foreach ($dataList as $data){
            if($name == $data->name) $ret['eq'] = $data->id;
            else $ret['like'] = true;
        }

        $this->returnAjaxSuccess($ret);
    }

    public function actionEditTheme()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $result = ThemeService::getService()->editInfo($id, ThemeModel::className());
            $this->log($result);
            if($result)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'theme-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['theme/theme-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $copy = ArrayHelper::getValue($this->paramData,'copy');
            $customer_id = ArrayHelper::getValue($this->paramData, 'customer_id');
            $model = ThemeModel::find()->where(['id' => $id])
                ->with('customer')
                ->asArray()->one();
            $barcode = '';
            $customer_id = ArrayHelper::getValue($model, 'customer.id', $customer_id);
            if($customer_id){
                $barcode = ThemeModel::find()->where(['customer_id' => $customer_id])->max('barcode');
                if($barcode){
                    $barcode = $barcode + 1;
                    $barcode = str_pad("$barcode", 4, "0", STR_PAD_LEFT);
                }
            }
            if($copy){
                unset($model['id']);
                unset($model['barcode']);
            }
            return $this->render('edit-theme',['model' => $model, 'barcode' => $barcode]);
        }
    }

    public function actionDeleteTheme()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);
        $ids = ArrayHelper::getValue($this->paramData,'ids');
        $return = ThemeService::getService()->deleteInfo($ids,ThemeModel::className());
        $this->log($return);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'theme-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['theme/theme-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionRelationMaterial()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $return = ThemeService::getService()->relationMaterial($this->paramData);
            if($return === true)
                return $this->returnAjaxSuccess([
                    'message' => '成功',
                    'navTabId' => 'theme-list',
                    'callbackType' => 'forward',
                    'forwardUrl'  => Url::to(['theme/theme-list'])
                ]);
            return $this->returnAjaxError($return);
        }else{
            return $this->render('relation-material');
        }
    }
}
