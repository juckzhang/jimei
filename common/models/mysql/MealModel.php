<?php
namespace common\models\mysql;

class MealModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%meal}}";
    }

    public function getBrand(){
        return $this->hasOne(BrandModel::className(), ['id' => 'brand_id'])
            ->select(['id','name','barcode']);
    }

    public function getPhone(){
        return $this->hasOne(PhoneModel::className(), ['id' => 'mobile_id'])
            ->select(['id','modal','barcode']);
    }

    public function getColor(){
        return $this->hasOne(ColorModel::className(), ['id' => 'color_id'])
            ->select(['id','name','barcode']);
    }

    public function getMaterial(){
        return $this->hasOne(MaterialModel::className(), ['id' => 'material_id'])
            ->select(['id','name','barcode']);
    }

    public function getCustomer(){
        return $this->hasOne(CustomerModel::className(), ['id' => 'customer_id'])
            ->select(['id','name','barcode']);
    }

    public function getTheme(){
        return $this->hasOne(ThemeModel::className(), ['id' => 'theme_id'])
            ->select(['id','name','barcode']);
    }
}