<?php
namespace common\models\mysql;

class MaterialModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%material}}";
    }
}