<?php
namespace console\controllers;
use yii\console\Controller;
/**
 * Class PlaySeriesController
 * @package console\controllers
 */
class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if(parent::beforeAction($action))
        {
            set_time_limit(0);
            ini_set('memory_limit','128M');
            return true;
        }
        return false;
    }
}