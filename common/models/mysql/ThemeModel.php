<?php
namespace common\models\mysql;

class ThemeModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%theme}}";
    }
}