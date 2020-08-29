<?php
namespace backend\controllers;

use common\services\UploadService;
use yii\helpers\ArrayHelper;

class UploadController extends BaseController
{
    public function actionUploadFile()
    {
        $type = ArrayHelper::getValue($this->paramData,'type');

        $result = UploadService::getService()->upload($type);

        if(is_array($result))
            return $this->returnSuccess($result);

        return $this->returnError($result);
    }
}
