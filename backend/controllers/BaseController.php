<?php
namespace backend\controllers;


use common\constants\CodeConstant;
use common\controllers\CommonController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class BaseController extends CommonController
{
    protected $paramData = [];
    public $layout = false;
    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        $action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $action=  strtolower($action);//转成小写
        if($action == 'site/login') return true;

        $auth=  Yii::$app->authManager;
        $isAjax = Yii::$app->request->getIsAjax();
        //未登录
        if (\Yii::$app->user->isGuest) {
            if ($isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = array(
                    'status' => -1,
                    'message' => '请先登录',
                    'url' => Yii::$app->getHomeUrl()
                );
                return false ;
            } else {
                $this->redirect(['site/login']);
                return false;
            }
        }
        $this->paramData = $this->parseParam();
        //超级管理员
        if(Yii::$app->user->identity->role_id == 0){
            return true;
        }
        if(!$auth->getPermission($action)){
            //该页面没有纳入权限管理
            return true;
        }
        if (!\Yii::$app->user->can($action)) {
            if ($isAjax) {
                Yii::$app->response->data = $this->returnAjaxError(CodeConstant::PERMISSION_DENIED);
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                Yii::$app->response->data = array(
//                    'status' => -1,
//                    'message' => '对不起,你无权进行此项操作',
//                );
                return false;
            } else {
                throw new ForbiddenHttpException('对不起！您无权进行此项操作,请联系系统管理员!',403);
            }
        } else {
            return parent::beforeAction($action);
        }
    }

    protected function _sortOrder($prefix = '')
    {
        $orderFiled = ArrayHelper::getValue($this->paramData,'orderField');
        if(!$orderFiled){
            $orderFiled = $prefix.'create_time';
        }
        $orderDesc  = ArrayHelper::getValue($this->paramData,'orderDirection');

        $desc = SORT_DESC;
        if($orderDesc == 'asc')  $desc = SORT_ASC;
        return [$orderFiled => $desc];
    }

    protected function parseParam()
    {
        //return \yii::$app->request->postGet(\yii::$app->params['requestParam'],[]);
        $_requestParam = Yii::$app->getRequest()->postGet();
        if(array_key_exists(Yii::$app->params['tokenName'],$_requestParam))
            unset($_requestParam[\yii::$app->params['tokenName']]);

        if(array_key_exists(Yii::$app->params['tokenName'],$_requestParam))
            unset($_requestParam[Yii::$app->params['tokenName']]);

        if(array_key_exists(Yii::$app->params['signName'],$_requestParam))
            unset($_requestParam[Yii::$app->params['signName']]);

        $_requestParam['userId'] = Yii::$app->user->identity->id;
        return $_requestParam;
    }

    public function returnAjaxSuccess(array $data = [],$code = CodeConstant::SUCCESS)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $return = $data;
        $return['statusCode'] = $code;
        return $return;
    }

    protected function returnAjaxError($code)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $return = ['statusCode' => '300','message' => $this->getErrorMessage($code)];
        return $return;
    }
}