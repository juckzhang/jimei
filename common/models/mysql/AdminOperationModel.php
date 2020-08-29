<?php
namespace common\models\mysql;

class AdminOperationModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin_operation}}";
    }
}