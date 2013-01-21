<?php
// change the following paths if necessary
//$yii = dirname(__FILE__) . '/../yii/framework/yii.php';
$yii = 'yii/framework/yii.php';
// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG', true);
/**
 * 定义处于开发阶段
 * 程序某些地方要调试 最好能够发现程序的阶段
 */
defined('DEV_STAGE') or define('DEV_STAGE',  true );
/**
 * use  ?debug=yes to open debug mode
 */
//defined('YII_DEBUG') or define('YII_DEBUG', isset($_GET['debug'])? true : false);
defined('YII_DEBUG') or define('YII_DEBUG',false);
defined('YS_CONTROLLER_HELP') or define('YS_CONTROLLER_HELP', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// 配置文件移到下面来 可以使用上面定义的常量了！
$config = dirname(__FILE__) . '/protected/config/front.php';
require_once($yii);

include_once(dirname(__FILE__) . '/protected/my/global.php');
include_once(dirname(__FILE__) . '/protected/components/YsWebApplication.php');

Yii::$classMap = require(dirname(__FILE__) . '/protected/config/classMap.php');

$app = Yii::createApplication('YsWebApplication', $config);

Yii::setPathOfAlias('my', Yii::getPathOfAlias('application.my'));
Yii::setPathOfAlias('widgets', Yii::getPathOfAlias('application.my.widgets'));
// import common usage util tools
Yii::import('my.utils.*');

Yii::import('application.vendors.*');

// First Import the extension
Yii::import("application.components.EZendAutoLoader2", true);
// And then call the loaded class
EZendAutoloader2::$prefixes = array('Zend', 'Apache');
EZendAutoLoader2::$basePaths = array(Yii::getPathOfAlias('application.vendors.SolrPhpClient'),);

Yii::registerAutoloader(array("EZendAutoLoader2", "loadClass"));

$app->runEnd('front');
