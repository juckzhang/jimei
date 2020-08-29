<?php
namespace backend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\DistributionModel;
use Yii;
use backend\services\DistributionService;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class DistributionController extends BaseController
{
    public function actionDistributionList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyword');
        $data = DistributionService::getService()->DistributionlList($_keyWord,$_page,$_prePage);
        return $this->render('distribution-list',$data);
    }

    public function actionEditDistribution()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = DistributionService::getService()->editDistribution($id);
            if($result instanceof Model)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'distribution-list',
                    'callbackType' => 'forward',
                    'forwardUrl' => Url::to(['distribution/distribution-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = DistributionModel::find()->where(['id' => $id])->asArray()->one();

            return $this->render('edit-distribution',['model' => $model]);
        }
    }

    public function actionDeleteDistribution()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = DistributionService::getService()->deleteDistribution($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'distribution-list',
                'callbackType' => 'forward',
                'forwardUrl'  => Url::to(['distribution/distribution-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
