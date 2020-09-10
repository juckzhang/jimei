<?php
namespace common\models\mysql;

class ThemeModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%theme}}";
    }

    public function getCustomer(){
        return $this->hasOne(CustomerModel::className(), ['id' => 'customer_id'])
            ->select(['id','name']);
    }
}