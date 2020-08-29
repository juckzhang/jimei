<?php
namespace common\filters;

use backend\models\mongodb\UserModel;
use common\components\RpcClient;
use Yii;
use yii\base\ActionFilter;
use yii\helpers\Url;
use yii\web\Response;

class AccessFilter extends ActionFilter
{
    private $userId = null;

    public function init()
    {
        $userInfo = Yii::$app->request->cookies->getValue('userInfo');
        if($userInfo != NULL)
            $this->userId = $userInfo['_id'];
//        $_token = Yii::$app->request->cookies->getValue('token');
//
//        if($_token !== null)
//        {
//            $_rpcClient  = new RpcClient();
//            $this->userId = $_rpcClient->getRemoteService('users/token','getIdByToken',['token' => (string)$_token]);
//        }
        parent::init();
    }

    public function beforeAction($action)
    {
        //判断是否是测试环境
        if(YII_ENV === 'development') return parent::beforeAction($action);

        $_userModel = UserModel::findOne(['_id' => (int)$this->userId]);
        //判断$userId是否有值
        if($_userModel === null)
        {
            Yii::$app->getResponse()->redirect(Url::to(['site/login']), 302);
            //$this->denyAccess(CodeConstant::USER_TOKEN_NOT_EXISTS);
            return false;
        }
        //判断用户是否锁住 或者 封号
        if($_userModel->status == UserModel::STATUS_FREEZE)
        {
            Yii::$app->getResponse()->redirect(Url::to(['site/login']), 302);
            //$this->denyAccess(CodeConstant::USER_IS_FREEZE);
            return false;
        }

        //判断用户是否锁住 或者 封号
        if($_userModel->status == UserModel::STATUS_DELETED)
        {
            Yii::$app->getResponse()->redirect(Url::to(['site/login']), 302);
            //$this->denyAccess(CodeConstant::USER_IS_DELETED);
            return false;
        }

        return parent::beforeAction($action);
    }

    protected function denyAccess($code)
    {
        $_return[Yii::$app->params['returnCode']] = $code;
        Yii::$app->lang->load('error_message');
        $message = Yii::$app->lang->line($code);
        $_return[Yii::$app->params['returnDesc']] = $message;

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        Yii::$app->getResponse()->data = $_return;
    }
}