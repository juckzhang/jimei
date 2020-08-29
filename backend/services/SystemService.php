<?php
namespace backend\services;

use common\constants\CodeConstant;
use common\helpers\DateHelper;
use common\helpers\TreeHelper;
use common\models\mysql\AdminModel;
use common\models\mysql\RoleModel;
use common\models\mysql\RoleSourceModel;
use common\models\mysql\SourceModel;
use common\models\mysql\UserModel;
use Yii;
use backend\services\base\BackendService;
use yii\helpers\ArrayHelper;

class SystemService extends BackendService
{
    public function sourceList($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $parentId = ArrayHelper::getValue($other,'parentId');

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = SourceModel::find()->where(['status' => SourceModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','name',$keyWord])
            ->andFilterWhere(['parent_id' => $parentId]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function sourceAll()
    {
        $models = SourceModel::find()->where(['status' => SourceModel::STATUS_ACTIVE])
            ->asArray()->all();

        $treeHelper = new TreeHelper();
        $models = $treeHelper->getTree($models);

        return $models;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function sources()
    {
        $models = SourceModel::find()
            ->select(['id','name'])
            ->where(['status' => SourceModel::STATUS_ACTIVE,'parent_id' => 0])
            ->asArray()->all();

        return $models;
    }

    public function editSource($id)
    {
         return $this->editInfo($id,SourceModel::className());
    }

    public function deleteSource($ids)
    {
        $num = $this->deleteInfo($ids,SourceModel::className());
        if($num > 0) {
            //删除role_source表中相关资源的数据
            RoleSourceModel::deleteAll(['source_id' => $ids]);
            return true;
        }
        return false;
    }

    public function roleList($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $cateId = ArrayHelper::getValue($other,'cateId');

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = RoleModel::find()->where(['status' => RoleModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','title',$keyWord])
            ->andFilterWhere(['catet_id' => $cateId]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function roleAll()
    {
        $models = RoleModel::find()->where(['status' => RoleModel::STATUS_ACTIVE])
            ->select(['id','name'])->all();

        return $models;
    }

    public function editRole($id,array $sources)
    {
        $model = $this->editInfo($id,RoleModel::className());
        if($model instanceof RoleModel)
        {
            $assign = $this->assignPrivilege($model->id,$sources);
            if($assign == true) return $model;

            return $assign;
        }

        return CodeConstant::PARAM_ERROR;
    }

    public function deleteRole($ids)
    {
        $num = $this->deleteInfo($ids,RoleModel::className());
        if( $num > 0 ) return true;

        return false;
    }

    public function assignPrivilege($role,array $sources)
    {
        $now = DateHelper::now();
        //重新组织数据
        $batchData = [];
        foreach($sources as $source)
        {
            $batchData[] = [$role,$source,$now,$now];
        }

        if(! is_numeric($role) OR empty($batchData)) return CodeConstant::PARAM_ERROR;

        //删除该角色的所有权限
        RoleSourceModel::deleteAll(['role_id' => $role]);
        //批量插入数据
        $rowNum = Yii::$app->db->createCommand()
            ->batchInsert(RoleSourceModel::tableName(), ['role_id', 'source_id','create_time','update_time'], $batchData)
            ->execute();

        if($rowNum > 0) return true;

        return CodeConstant::PARAM_ERROR;
    }

    public function userList($keyWord,$other,array $order = [],$page,$prePage)
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $cateId = ArrayHelper::getValue($other,'cateId');

        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = AdminModel::find()->where(['status' => UserModel::STATUS_ACTIVE])
            ->andFilterWhere(['like','title',$keyWord])
            ->andFilterWhere(['catet_id' => $cateId]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->all();

        return $data;
    }

    public function editUser($id)
    {
        return $this->editInfo($id,AdminModel::className());
    }

    public function deleteUser($ids)
    {
        $num = $this->deleteInfo($ids,AdminModel::className());
        if( $num > 0 ) return true;

        return false;
    }
}

