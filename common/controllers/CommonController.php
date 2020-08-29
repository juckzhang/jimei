<?php
/**
 * Created by PhpStorm.
 * User: chao zhang
 * Date: 2016/10/01
 * Time: 13:53
 */
namespace common\controllers;

use Yii;
use yii\web\Controller;
use common\constants\CodeConstant;
use yii\base\InvalidParamException;
use yii\web\Response;


class CommonController extends Controller
{
    /**
     * 请求出错 返回信息
     *
     * @param int $code
     * @return string
     */
    protected function returnError($code) {
        $message = $this->getErrorMessage($code);
        return $this->returnResult($code,NULL,$message);
    }

    /**
     * 根据错误状态吗获取错误信息
     * @param $code
     * @return mixed
     */
    protected function getErrorMessage($code)
    {
        Yii::$app->lang->load('error_message');
        $message = Yii::$app->lang->line($code);

        return $message;
    }

    /**
     * 请求成功 返回
     *
     * @param null $data
     * @param int $code
     * @return string
     */
    protected function returnSuccess($code = CodeConstant::SUCCESS,$data = NULL){
        $message = null;

        if( ! is_int($code))
        {
            $data = $code;
            $code = CodeConstant::SUCCESS;
        }

        if($code !== CodeConstant::SUCCESS)
        {
            Yii::$app->lang->load('success_message');
            $message = Yii::$app->lang->line($code);
        }
        return $this->returnResult(CodeConstant::SUCCESS,$data,$message);
    }

    /**
     * @param $code
     * @param null $data
     * @param null $desc
     * @return mixed
     * @throws InvalidParamException
     */
    public function returnResult($code,$data = NULL,$desc = NULL){
        if( ! is_numeric($code))
            throw new InvalidParamException('code not exists!');

        $_return[Yii::$app->params['returnCode']] = $code;
        if( $data !== NULL)
            $_return[Yii::$app->params['returnData']] = $data;

        if($desc !== NULL)
            $_return[Yii::$app->params['returnDesc']] = $desc;

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return $_return;
    }
}