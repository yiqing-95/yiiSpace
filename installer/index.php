<?php
// change the following paths if necessary
//$yii=dirname(__FILE__).'/../../yii/framework/yii.php';
$yii = 'yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

/*
if(!file_exists($yii))
{
    die("<font color=\"red\">Error:</font> Cannot find Yii!<br/>
            Please check and correct the path to Yii in <em>{$_SERVER['SERVER_NAME']}/installer/index.php</em> and <em>{$_SERVER['SERVER_NAME']}/index.php</em>");
}
*/
if(file_exists(dirname(__FILE__).'/lock')) {
    die("<font color=\"red\">Attention:</font> Installer has been locked.<br/>
        Please remove the lock file in the installer directory and try again.");
}
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',2);
require_once($yii);
Yii::createWebApplication($config)->run();
