<?php
namespace common\models\mysql;

class OrderModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%order}}";
    }

    public function getTheme()
    {
        return $this->hasOne(ThemeModel::className(),['theme_id' => 'id'])
            ->where(['status' => ThemeModel::STATUS_ACTIVE])
            ->select(['id','name','template_url'])
            ->asArray();
    }

    public function getPhone()
    {
        return $this->hasOne(PhoneModel::className(), ['mobile_id' => 'id'])
            ->select(['id','width','height','modal'])
            ->asArray();
    }

    public function getMaterial()
    {
        return $this->hasOne(MaterialModel::className(), ['material_id' => 'id'])
            ->select(['id','name'])
            ->asArray();
    }

    public function getColor()
    {
        return $this->hasOne(ColorModel::className(), ['color_id' => 'id'])
            ->select(['id','name'])
            ->asArray();
    }

    public function getRelat(){
        return $this->hasOne(MaterialPhoneModel::className(), ['mobile_id' => 'mobile_id','material_id' => 'material_id'])
            ->asArray();
    }
}