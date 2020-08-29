<?php
namespace common\models\mysql;

class AdminModel extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin}}";
    }

    public function rules()
    {
        parent::rules();
        return [
            [['username','password'],'required'],
            ['password','encryptPassword'],
        ];
    }

    public function encryptPassword($attribute)
    {
        if($attribute == 'password')
            $this->password = \Yii::$app->security->generatePasswordHash($this->password);
    }
}