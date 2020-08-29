<?php
namespace common\models\mysql;

class UserModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%user}}";
    }
}