<?php
namespace common\models\mysql;

class MaterialModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%material}}";
    }

    public function getColor(){
        return $this->hasMany(ColorMaterialModel::className(),['material_id' => 'id'])->with('color');
    }

    public function getPhone(){
        return $this->hasMany(MaterialPhoneModel::className(), ['material_id' => 'id']);
    }
}