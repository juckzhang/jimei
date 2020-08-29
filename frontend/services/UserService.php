<?php

namespace frontend\services;

use common\models\mysql\FeedbackModel;
use common\models\mysql\UserCharsModel;
use common\models\mysql\UserModel;
use frontend\services\base\FrontendService;
use common\constants\CodeConstant;
use yii\helpers\ArrayHelper;
use yii\log\EmailTarget;

class UserService extends FrontendService{

    public function login(array $params){
        $data = [
            'third_account' => ArrayHelper::getValue($params, 'third_account'),
            'account_type' => ArrayHelper::getValue($params, 'account_type'),
            'nick_name' => ArrayHelper::getValue($params, 'nick_name', 'Guest'.mt_rand(100000, 999999)),
            'icon_url' => ArrayHelper::getValue($params,'icon_url', \Yii::$app->params['domain'].'upload/default_icon_url.jpg'),
        ];
        if(!$data['third_account'] or ! in_array($data['account_type'], ['1', '2', '3'])){
            return CodeConstant::PARAM_ERROR;
        }
        //判断账号是否存在
        $where = ['third_account' => $data['third_account'], 'account_type' => $data['account_type']];
        $model = UserModel::find()
            ->select(['id','nick_name','icon_url','third_account','account_type'])
            ->where($where)
            ->one();

        if(!($model instanceof UserModel)){
            $model = new UserModel();
        }elseif ($data['account_type'] == 3){
            unset($data['nick_name']);
        }
        $data['access_token'] = md5($params['third_account'] .'-'.$params['account_type'].'-'.time().'-'.mt_rand());
        if($model->load(['UserModel' => $data]) and $model->save()){
            $model->id = (string)$model->id;
            return $model->toArray();
        }

        return CodeConstant::USER_LOGIN_FAILED;
    }

    // 用户反馈
    public function feedback($params)
    {
        if(empty($params['user_id'])){
            return CodeConstant::USER_TOKEN_NOT_EXISTS;
        }
        $model = new FeedbackModel();
        if($model->load($params,'') and $model->save()){
            $model->id = (string)$model->id;
            return $model->toArray();
        }

        return CodeConstant::FEED_BACK_FAILED;
    }

    // 我的反馈列表
    public function feedbackList($userId, $page, $count)
    {
        if(empty($userId)){
            return CodeConstant::USER_TOKEN_NOT_EXISTS;
        }
        list($offset,$limit) = $this->parsePageParam($page, $count);
        $data = ['dataList' => [],'pageCount' => 0,'dataCount' => 0];
        $models = FeedbackModel::find()
            ->where([
                'user_id' => $userId,
                'status' => FeedbackModel::STATUS_ACTIVE,
                'parent_id' => 0,
            ]);
        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
        {
            $models = $models->orderBy(['create_time' => SORT_DESC])->asArray()
                ->with('feedback')
                ->offset($offset)->limit($limit)->all();
            $data['dataList'] = $models;
        }

        return $data;
    }

    public function knowChars($data){
        if(empty($data['user_id'])){
            return CodeConstant::USER_TOKEN_NOT_EXISTS;
        }
        $model = new UserCharsModel();
        if($model->load($data,'') and $model->save()){
            return CodeConstant::SUCCESS;
        }

        return CodeConstant::FEED_BACK_FAILED;
    }

    public function showChars($userId){
        if(empty($userId)){
            return CodeConstant::USER_TOKEN_NOT_EXISTS;
        }
        $ret = [];
        //获取当前用户的信息
        $data = UserCharsModel::find()->where([
            'user_id' => $userId,
            'status' => UserCharsModel::STATUS_ACTIVE
        ])->orderBy(['create_time' => SORT_DESC])->asArray()->one();
        if(empty($data)){
            return $ret;
        }
        $charNum = $data['char_num'];
        //获取低于分数的人数
        $num = UserCharsModel::find()->where(['<', 'char_num', $charNum])->count();
        //参与总人数
        $totalNum = UserCharsModel::find()->count();

        return ['rate' => ceil(($num+100)*100/($totalNum+150)).'%', 'charNum' => ceil($charNum/80*3755*1.2)];
    }
}