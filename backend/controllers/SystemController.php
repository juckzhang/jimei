<?php
namespace backend\controllers;

use common\models\mysql\RoleModel;
use common\models\mysql\RoleSourceModel;
use common\models\mysql\UserModel;
use common\models\mysql\AdminModel;
use Yii;
use backend\services\SystemService;
use common\constants\CodeConstant;
use common\models\mysql\SourceModel;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class SystemController extends BaseController
{
    public function actionSourceList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = SystemService::getService()->sourceList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('source-list',$data);
    }

    public function actionEditSource()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = SystemService::getService()->editSource($id);

            if($result instanceof ActiveRecord)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'source-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['system/source-list'])
                ]);
            return $this->returnAjaxError($result);
        }else{
            //获取广告id
            $id = ArrayHelper::getValue($this->paramData,'id');
            $model = SourceModel::find()->where(['id' => $id])->asArray()->one();
            return $this->render('edit-source',['model' => $model]);
        }
    }

    public function actionDeleteSource()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = SystemService::getService()->deleteSource($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'source-list',
                'callbackType' => 'closeCurrent',
                'forwardUrl'  => Url::to(['system/source-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionRoleList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = SystemService::getService()->roleList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('role-list',$data);
    }

    public function actionEditRole()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $sources = ArrayHelper::getValue($this->paramData,'sources');
            $result = SystemService::getService()->editRole($id,$sources);

            if($result instanceof ActiveRecord)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'role-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['system/role-list'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $model = RoleModel::find()->where(['id' => $id])->asArray()->one();

            $models = RoleSourceModel::find()->where(['role_id' => $id])->asArray()->all();
            $models = ArrayHelper::getColumn($models,'source_id');
            return $this->render('edit-role', ['model' => $model,'selected' => $models]);
        }
    }

    public function actionDeleteRole()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = SystemService::getService()->deleteRole($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'role-list',
                'callbackType' => 'closeCurrent',
                'forwardUrl'  => Url::to(['system/role-list'])
            ]);
        return $this->returnAjaxError($return);
    }

    public function actionAssignPrivilege()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $sources = ArrayHelper::getValue($this->paramData,'sources');
            $result = SystemService::getService()->assignPrivilegee($id,$sources);

            if($result === true)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'role-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['system/role-list'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $models = RoleSourceModel::find()->where(['id' => $id])->asArray()->all();
            $models = ArrayHelper::getColumn($models,'source_id');

            //选择所有的资源
            $sources = SystemService::getService()->sources();
            //获取所有的文章分类信息
            $cateModels = SystemService::getService()->categoryList(null, [], $this->_sortOrder(), 1, PHP_INT_MAX);
            return $this->render('edit-role-source', ['sources' => $sources, 'selected' => $models]);
        }
    }

    public function actionUserList()
    {
        $_prePage  = ArrayHelper::getValue($this->paramData,'numPerPage');
        $_page       = ArrayHelper::getValue($this->paramData,'pageNum');
        $_keyWord  = ArrayHelper::getValue($this->paramData,'keyWord');
        $_other    = ArrayHelper::getValue($this->paramData,'other',[]);
        $_order = $this->_sortOrder();

        $data = SystemService::getService()->userList($_keyWord,$_other,$_order,$_page,$_prePage);
        return $this->render('user-list',$data);
    }

    public function actionEditUser()
    {
        if(\Yii::$app->request->getIsPost())
        {
            $id = ArrayHelper::getValue($this->paramData,'id');
            $result = SystemService::getService()->editUser($id);

            if($result instanceof ActiveRecord)
                return $this->returnAjaxSuccess([
                    'message' => '编辑成功',
                    'navTabId' => 'user-list',
                    'callbackType' => 'closeCurrent',
                    'forwardUrl' => Url::to(['system/user-list'])
                ]);
            return $this->returnAjaxError($result);
        }else {
            $id = ArrayHelper::getValue($this->paramData, 'id');
            $model = AdminModel::find()->where(['id' => $id])->asArray()->one();
            //获取所有的文章分类信息
            $roles = SystemService::getService()->roleAll();
            return $this->render('edit-user', ['model' => $model, 'roles' => $roles]);
        }
    }

    public function actionDeleteUser()
    {
        if(! Yii::$app->request->getIsAjax()) return $this->returnAjaxError(CodeConstant::REQUEST_METHOD_ERROR);

        $ids = ArrayHelper::getValue($this->paramData,'ids');

        $return = SystemService::getService()->deleteUser($ids);
        if($return === true)
            return $this->returnAjaxSuccess([
                'message' => '删除成功',
                'navTabId' => 'user-list',
                'callbackType' => 'closeCurrent',
                'forwardUrl'  => Url::to(['system/user-list'])
            ]);
        return $this->returnAjaxError($return);
    }
}
