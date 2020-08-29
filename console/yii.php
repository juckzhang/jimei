<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'development');

//require(__DIR__ . '/../common/config/autoload.php');
require(__DIR__ . '/../vendor/autoload.php');

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../common/config/bootstrap.php');
require(__DIR__ . '/config/bootstrap.php');

//$config = \common\helpers\CommonHelper::loadConfig('main.php',['@common','@console']);
$config = require(__DIR__ . '/config/main.php');

$application = new \yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);