<?php

$vendorDir = dirname(__DIR__);

return array (
  'bower/bower-asset' => 
  array (
    'name' => 'bower/bower-asset',
    'version' => '9999999-dev',
    'alias' => 
    array (
      '@bower' => $vendorDir . '/bower/bower-asset',
    ),
  ),
  'yiisoft/yii2-bootstrap' => 
  array (
    'name' => 'yiisoft/yii2-bootstrap',
    'version' => '2.0.10.0',
    'alias' => 
    array (
      '@yii/bootstrap' => $vendorDir . '/yiisoft/yii2-bootstrap/src',
    ),
  ),
  'yiisoft/yii2-swiftmailer' => 
  array (
    'name' => 'yiisoft/yii2-swiftmailer',
    'version' => '2.1.2.0',
    'alias' => 
    array (
      '@yii/swiftmailer' => $vendorDir . '/yiisoft/yii2-swiftmailer/src',
    ),
  ),
  'yiisoft/yii2-imagine' => 
  array (
    'name' => 'yiisoft/yii2-imagine',
    'version' => '2.1.1.0',
    'alias' => 
    array (
      '@yii/imagine' => $vendorDir . '/yiisoft/yii2-imagine/src',
    ),
  ),
  'crazydb/yii2-ueditor' => 
  array (
    'name' => 'crazydb/yii2-ueditor',
    'version' => '1.6.4.0',
    'alias' => 
    array (
      '@crazydb/ueditor' => $vendorDir . '/crazydb/yii2-ueditor',
    ),
  ),
);
