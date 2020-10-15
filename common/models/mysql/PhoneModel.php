<?php
namespace common\models\mysql;

class PhoneModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%phone}}";
    }

    public function getBrand(){
        return $this->hasOne(BrandModel::className(), ['id' => 'brand_id'])
            ->select(['id','name','barcode']);
    }
}