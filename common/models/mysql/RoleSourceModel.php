<?php
namespace common\models\mysql;

class RoleSourceModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%role_source}}";
    }
}