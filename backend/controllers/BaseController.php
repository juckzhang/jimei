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

    private $startTime;

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
        $this->startTime = microtime(true);
        $action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $action=  strtolower($action);//转成小写
        if($action == 'site/login') return true;

        $auth=  Yii::$app->authManager;
        $isAjax = Yii::$app->request->getIsAjax();
        //未登录
        if(!$this->checkLogin($isAjax)) return false;
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
                return false;
            } else {
                throw new ForbiddenHttpException('对不起！您无权进行此项操作,请联系系统管理员!',403);
            }
        } else {
            return parent::beforeAction($action);
        }
    }

    protected function _sortOrder()
    {
        $orderFiled = ArrayHelper::getValue($this->paramData,'orderField');
        $orderDesc  = ArrayHelper::getValue($this->paramData,'orderDirection','desc');
        if(!$orderFiled) $orderFiled = 'create_time';
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

    private function checkLogin($isAjax){
        if (\Yii::$app->user->isGuest) {
            $this->noticeLogin($isAjax);
            return false;
        }

        $user = \Yii::$app->user->identity;
        $session = Yii::$app->getSession();
        $authKey = $session->get('identify_auth_key');

        if($user->auth_key !== $authKey){
            $this->noticeLogin($isAjax, '您的账号在其他设备上已登录!');
            Yii::$app->user->logout();
            return false;
        }

        return true;
    }

    private function noticeLogin($isAjax, $message = '请先登录'){
        if ($isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = array(
                'statusCode' => 301,
                'message' => $message,
                'navTabId' => 'logout',
                'callbackType' => 'forward',
                'forwardUrl' => Url::to(['site/login']),
            );
        } else {
            $this->redirect(['site/login']);
        }
    }

    protected function log($result = null){
        $res = is_object($result) ? ArrayHelper::toArray($result) : $result;
        \Yii::$app->bizLog->log([
            'action' => Yii::$app->controller->id . '|' . Yii::$app->controller->action->id,
            'user' => \Yii::$app->user->identity->username,
            'params' => $this->paramData,
            'result' => $res,
            'time' => sprintf('%.3f',microtime(true) - $this->startTime),
        ],'req','Info');
    }
}