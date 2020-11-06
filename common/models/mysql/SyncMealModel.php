<?php
namespace common\models\mysql;

class SyncMealModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%sync_meal}}";
    }

    public function getCustomer(){
        return $this->hasOne(CustomerModel::className(), ['id' => 'customer_id']);
    }
}