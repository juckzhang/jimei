<?php
namespace common\models\mysql;

class CustomerModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%customer}}";
    }
}