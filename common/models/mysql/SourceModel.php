<?php
namespace common\models\mysql;

class SourceModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%source}}";
    }
}