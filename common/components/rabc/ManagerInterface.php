<?php

namespace common\components\rabc;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/11
 * Time: 0:42
 */
interface ManagerInterface
{
    public function checkAccess($userId, $permissionName, $params = []);

    public function getPermission($name);

    public function getPermissions();

    public function getPermissionsByRole($roleId);

    public function getPermissionsByUser($userId);
}