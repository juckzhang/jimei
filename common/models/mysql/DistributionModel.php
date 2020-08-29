<?php
namespace common\models\mysql;

class DistributionModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%base_list}}";
    }
}