<?php
namespace common\models\mysql;

class PhoneModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%phone}}";
    }

    public function getBrand(){
        return $this->hasOne(BrandModel::className(), ['brand_id' => 'id'])
            ->select(['id','name']);
    }
}