<?php
namespace common\models\mysql;

class PrePaymentModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%pre_payment}}";
    }

    public function getCustomer(){
        return $this->hasOne(CustomerModel::className(), ['id' => 'customer_id'])
            ->asArray();
    }

    public function getTheme()
    {
        return $this->hasOne(ThemeModel::className(),['id' => 'theme_id'])
            ->asArray();
    }
}