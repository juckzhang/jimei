<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/11
 * Time: 0:45
 */

namespace common\components\rabc;

use yii\caching\Cache;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

class DbManager  extends BaseManager implements ManagerInterface
{

    public $db          = 'db';
    public $sourceTable = '{{%source}}';
    public $roleTable   = '{{%role}}';
    public $assignTable = '{{%role_source}}';
    public $userTable   = '{{%admin}}';
    public $cache       = 'cache';

    protected $cachePermissions;
    protected $cacheUserPermissions;

    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
    }

    /**
     * 检查用户的权限
     * @param $userId
     * @param $permissionName
     * @param array $params
     * @return bool
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        //判断$permissionName是否在限制的资源列表中
        $sourceId = $this->getPermission($permissionName, $params);
        if(!$sourceId) return true;

        //根据用户id获取用户的所有权限
        $userPermissions = $this->getPermissionsByUser($userId);

        if(in_array($sourceId,$userPermissions)) return true;

        return false;
    }

    /**
     * 查询资源是否是在限制列表内
     * @param $name
     * @param array $params
     * @return bool
     */
    public function getPermission($name,array $params = [])
    {
        $query = new Query();
        $permission  = $query->select(['id'])
            ->from($this->sourceTable)
            ->where(['request' => $name,'status' => 0])
            ->one($this->db);

        $source = 0;
        if($permission != false) $source = $permission['id'];
        return $source;
    }

    /**
     * 获取所有的权限列表
     * @return array
     */
    public function getPermissions()
    {
        if(is_array($this->cachePermissions)) return $this->cachePermissions;

        $query = new Query();
        $result = $query->select(['id'])
            ->from($this->sourceTable)
            ->where(['status' => 0])
            ->column($this->db);
        $this->cachePermissions = $result;
        return $this->cachePermissions;
    }

    /**
     * 根据角色返回角色对应的权限
     * @param $roleId
     * @return array
     */
    public function getPermissionsByRole($roleId)
    {
        if(is_array($this->cacheUserPermissions)) return $this->cacheUserPermissions;

        $query = new Query();
        $result = $query->select(['source_id'])
            ->from($this->assignTable)
            ->where(['role_id' => $roleId,'status' => 0])
            ->column($this->db);
        $this->cacheUserPermissions = $result;
        return $this->cacheUserPermissions;
    }

    /**
     * 根据用户id返回用户的所有权限
     * @param $userId
     * @return array
     */
    public function getPermissionsByUser($userId)
    {
        //根据用户id获取用户对应的角色
        $query = $query = new Query();
        $user = $query->select(['role_id'])
            ->from($this->userTable)
            ->where(['id' => $userId,'status' => 0])
            ->one();
        $roleId = $user['role_id'];

        if($roleId == 0) return $this->getPermissions();

        else return $this->getPermissionsByRole($roleId);
    }

}