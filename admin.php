<?php

// change the following paths if necessary
//$yii = dirname(__FILE__) . '/../yii/framework/yii.php';
$yii =  'yii/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/back.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
require_once($yii);

include_once(dirname(__FILE__) . '/protected/my/global.php');

Yii::$classMap = require(dirname(__FILE__) . '/protected/config/classMap.php');

include_once(dirname(__FILE__) . '/protected/components/YsAdminWebApplication.php');

$app = Yii::createApplication('YsAdminWebApplication', $config);

Yii::setPathOfAlias('my', Yii::getPathOfAlias('application.my'));
Yii::setPathOfAlias('widgets', Yii::getPathOfAlias('application.my.widgets'));
// import common usage util tools
Yii::import('my.utils.*');

Yii::import('application.vendors.*');

$app->runEnd('back');

