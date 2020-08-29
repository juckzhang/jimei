<?php
namespace frontend\controllers;

use common\constants\CodeConstant;
use common\models\mysql\UserModel;
use Yii;
use common\controllers\CommonController;
use yii\helpers\ArrayHelper;

class BaseController extends CommonController
{
    protected $paramData = [];
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        $this->paramData = $this->parseParam();

        return parent::beforeAction($action);
    }

    public function getParamData()
    {
        return $this->paramData;
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    protected function parseParam()
    {
        $_requestParam = Yii::$app->getRequest()->getPost();
        if(is_object($_requestParam)){
            $_requestParam = json_decode($_requestParam,true);
        }

        return $_requestParam;
    }
}
