<?php
namespace common\models\mysql;

class ThemeModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%theme}}";
    }

    public function getCustomer(){
        return $this->hasOne(CustomerModel::className(), ['id' => 'customer_id'])
            ->select(['id','name']);
    }

    public function getMaterial(){
        return $this->hasMany(ThemeMaterialModel::className(), ['theme_id' => 'theme_id'])
            ->select(['id','theme_id','material_id'])->with('material');
    }
}