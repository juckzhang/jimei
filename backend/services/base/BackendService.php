<?php
namespace backend\services\base;

use common\services\base\Service;
class BackendService extends Service
{
    protected function parsePageParam($page,$prePage)
    {
        if(is_numeric($prePage))
            \Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'prePage',
                'value' => $prePage,
                'expire' => 7*24*60*60,
            ]));
        $limit = is_numeric($prePage) ? (int)$prePage : static::DEFAULT_PRE_PAGE;
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
}

