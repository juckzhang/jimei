<?php
namespace common\models\mysql;

class BrandModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%brand}}";
    }
}