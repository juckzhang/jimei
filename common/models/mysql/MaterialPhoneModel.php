<?php
namespace common\models\mysql;

class MaterialPhoneModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%phone_material_relation}}";
    }

    public function getPhone(){
        return $this->hasOne(PhoneModel::className(), ['id' => 'mobile_id'])
            ->select(['id','modal','brand_id','barcode'])->with('brand');
    }

    public function getMaterial(){
        return $this->hasOne(MaterialModel::className(), ['id' => 'material_id'])
            ->select(['id','name','barcode']);
    }
}