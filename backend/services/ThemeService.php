<?php
namespace backend\services;

use common\models\mysql\ThemeModel;
use backend\services\base\BackendService;

class ThemeService extends BackendService
{
    public function themeList($keyWord,$page,$prePage,array $order = [])
    {
        list($offset,$limit) = $this->parsePageParam($page,$prePage);
        $data = ['pageCount' => 0,'dataList' => [],'dataCount' => 0];

        $models = $cardModels = ThemeModel::find()
            ->where(['!=','status' , ThemeModel::STATUS_DELETED])
            ->andFilterWhere(['name','title',$keyWord]);

        $data['dataCount'] = $models->count();
        $data['pageCount'] = $this->reckonPageCount($data['dataCount'],$limit);

        if($data['pageCount'] > 0 AND $page <= $data['pageCount'])
            $data['dataList'] = $models->orderBy($order)->limit($limit)->offset($offset)->asArray()->all();

        return $data;
    }

    public function editTheme($id)
    {
        return $this->editInfo($id,ThemeModel::className());
    }

    public function deleteTheme($id)
    {
        return $this->deleteInfo($id,ThemeModel::className());
    }
}

