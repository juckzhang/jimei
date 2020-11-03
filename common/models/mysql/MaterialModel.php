<?php
namespace common\models\mysql;

class MaterialModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%material}}";
    }

    public function getColor(){
        return $this->hasMany(ColorMaterialModel::className(),['id' => 'color_id'])->with('color');
    }
}