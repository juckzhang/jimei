<?php
namespace common\models\mysql;

class OrderModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%test_order}}";
    }

    public function getBrand(){
        return $this->hasOne(BrandModel::className(), ['id' => 'brand_id'])
            ->select(['id','name', 'barcode'])
            ->asArray();
    }

    public function getCustomer(){
        return $this->hasOne(CustomerModel::className(), ['id' => 'customer_id'])
            ->select(['id','name', 'barcode'])
            ->asArray();
    }

    public function getTheme()
    {
        return $this->hasOne(ThemeModel::className(),['id' => 'theme_id'])
            ->select(['id','name','template_url','status','barcode','left_template_url','right_template_url'])
            ->asArray();
    }

    public function getPhone()
    {
        return $this->hasOne(PhoneModel::className(), ['id' => 'mobile_id'])
            ->select(['id','width','height','modal','canvas_type','status', 'barcode'])
            ->asArray();
    }

    public function getMaterial()
    {
        return $this->hasOne(MaterialModel::className(), ['id' => 'material_id'])
            ->select(['id','name','barcode'])
            ->asArray();
    }

    public function getColor()
    {
        return $this->hasOne(ColorModel::className(), ['id' => 'color_id'])
            ->select(['id','name','barcode'])
            ->asArray();
    }

    public function getSn()
    {
        return $this->hasOne(DistributionModel::className(), ['id' => 'base_id'])
            ->select(['id','sn'])
            ->asArray();
    }

    public function getRelat(){
        return $this->hasOne(MaterialPhoneModel::className(), ['mobile_id' => 'mobile_id','material_id' => 'material_id'])
            ->asArray();
    }
}