<?php
namespace backend\services\base;

use common\constants\Constant;
use common\services\base\Service;
use yii\helpers\ArrayHelper;

class BackendService extends Service
{
    protected function parsePageParam($page,$prePage)
    {
        $limit = is_numeric($prePage) ? (int)$prePage : Constant::DEFAULT_PRE_PAGE;
        $offset = (is_numeric($page) AND $page > 0 ) ? ($page - 1) * $limit : static::DEFAULT_PAGE;
        return [(int)$offset,(int)$limit];
    }

    public function deleteInfo($id,$modelName)
    {
        $primaryKey = $modelName::primaryKey()[0];
        $num = $modelName::deleteAll([$primaryKey => $id]);
        if($num > 0) return true;
        return false;
    }

    public function editInfo($id,$modelName)
    {
        $model = $modelName::findOne($id);
        if($model == null) $model = new $modelName();
        if($model->load(\Yii::$app->request->post()) && $model->save()) return $model;

        return false;
    }

    protected function checkInfo($id,$modelName,$status)
    {
        $primaryKey = $modelName::primaryKey()[0];
        $num = $modelName::updateAll(['status' => $status],[$primaryKey => $id]);
        if($num > 0) return true;
        return false;
    }

    protected function updateInfo($id, $modelName, $filed){
        $primaryKey = $modelName::primaryKey()[0];
        $num = $modelName::updateAll($filed,[$primaryKey => $id]);
        if($num > 0) return true;
        return false;
    }

    protected function handlerPic($item, $filed, $default = ''){
        $pic = ArrayHelper::getValue($item, $filed);
        if(!empty($pic)) $pic = \Yii::$app->params['picUrlPrefix'] . $pic;
        else $pic = $default;

        return $pic;
    }
}

