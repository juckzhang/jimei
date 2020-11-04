<?php
namespace common\models\mysql;

class AdminCustomerModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%user_customer}}";
    }

    public function getUser(){
        return $this->hasOne(AdminModel::className(),['id' => 'user_id']);
    }

    public function getCustomer(){
        return $this->hasOne(CustomerModel::className(), ['id' => 'customer_id']);
    }
}