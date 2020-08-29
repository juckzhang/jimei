<?php
namespace common\models\mysql;

class PhoneModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%phone}}";
    }
}