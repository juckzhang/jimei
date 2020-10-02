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
        return $this->hasOne(ThemeModel::className(),['id' => 'theme_id'])
            ->where(['status' => ThemeModel::STATUS_ACTIVE])
            ->select(['id','name','template_url'])
            ->asArray();
    }

    public function getPhone()
    {
        return $this->hasOne(PhoneModel::className(), ['id' => 'mobile_id'])
            ->select(['id','width','height','modal'])
            ->asArray();
    }

    public function getMaterial()
    {
        return $this->hasOne(MaterialModel::className(), ['id' => 'material_id'])
            ->select(['id','name'])
            ->asArray();
    }

    public function getColor()
    {
        return $this->hasOne(ColorModel::className(), ['id' => 'color_id'])
            ->select(['id','name'])
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