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

    public function getLefttheme(){
        return $this->hasOne(LeftThemeModel::className(), ['id' => 'left_theme_id'])
            ->select(['id','name','barcode']);
    }

    public function getRighttheme(){
        return $this->hasOne(RightThemeModel::className(), ['id' => 'right_theme_id'])
            ->select(['id','name','barcode']);
    }

    public function getSidetheme()
    {
        return $this->hasOne(SideThemeModel::className(),['id' => 'side_theme_id'])
            ->select(['id','name','left_template_url','left_template_url','status','barcode'])
            ->asArray();
    }
}