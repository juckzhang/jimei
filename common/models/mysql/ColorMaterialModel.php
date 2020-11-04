<?php
namespace common\models\mysql;

class ColorMaterialModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%color_material}}";
    }

    public function getColor(){
        return $this->hasOne(ColorModel::className(), ['id' => 'color_id'])
            ->select(['id','name']);
    }

    public function getMaterial(){
        return $this->hasOne(MaterialModel::className(), ['id' => 'material_id'])
            ->select(['id','name']);
    }
}