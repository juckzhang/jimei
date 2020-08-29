<?php
namespace common\models\mysql;

class RoleModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%role}}";
    }
}