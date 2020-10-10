<?php
namespace common\models\mysql;

class ThemeMaterialModel extends ActiveRecord
{

    public static function tableName()
    {
        return "{{%theme_material}}";
    }

    public function getTheme(){
        return $this->hasOne(ThemeModel::className(), ['id' => 'theme_id'])
            ->select(['id','name']);
    }

    public function getMaterial(){
        return $this->hasOne(MaterialModel::className(), ['id' => 'material_id'])
            ->select(['id','name']);
    }
}