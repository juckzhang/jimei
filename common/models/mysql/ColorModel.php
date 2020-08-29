<?php
namespace common\models\mysql;

class ColorModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%color}}";
    }
}