<?php
// 跟主文件同一个入口  可以用来切换debug状态来切换效果
// change the following paths if necessary
//$yii = dirname(__FILE__) . '/../../my/yii/framework/yii.php';
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
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YS_CONTROLLER_HELP') or define('YS_CONTROLLER_HELP', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// 导入composer生成的自动加载类
require_once(dirname(__FILE__).'/vendor/autoload.php');

// 配置文件移到下面来 可以使用上面定义的常量了！
$config = dirname(__FILE__) . '/protected/config/front.php';
require_once($yii);

// 设置composer vendor 别名
Yii::setPathOfAlias('composerVendor', dirname(__FILE__). DIRECTORY_SEPARATOR .'vendor');

include_once(dirname(__FILE__) . '/protected/my/global.php');
include_once(dirname(__FILE__) . '/protected/components/YsWebApplication.php');

Yii::$classMap = require(dirname(__FILE__) . '/protected/config/classMap.php');

$app = Yii::createApplication('YsWebApplication', $config);
Yii::setPathOfAlias('Elastica',Yii::getPathOfAlias('application.vendors.Elastica'));

Yii::setPathOfAlias('my', Yii::getPathOfAlias('application.my'));
Yii::setPathOfAlias('foy', Yii::getPathOfAlias('application.foy'));

Yii::setPathOfAlias('widgets', Yii::getPathOfAlias('application.widgets'));
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
