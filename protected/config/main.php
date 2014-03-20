<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
Yii::setPathOfAlias('yupe', dirname(__FILE__) . '/../modules/yupe/');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'YiiSpace',
    'language' => 'zh_cn',
    'charset' => "UTF-8",

    'import' => require(dirname(__FILE__) . '/imports.php'),

    // application components
    'components' =>  require(dirname(__FILE__) . '/components.php'),

    // preloading 'log' component
    'preload' => require(dirname(__FILE__) . '/preloads.php'),

    'modules' => require(dirname(__FILE__) . '/modules.php'),

    'params' => require(dirname(__FILE__) . '/params.php'),

    'behaviors' => require(dirname(__FILE__) . '/behaviors.php'),

    'controllerMap' => require(dirname(__FILE__) . '/controllerMaps.php'),

    'aliases' => array(
        //assuming you extracted the files to the extensions folder
        //  'xupload' => 'ext.xupload',
        'wij' => 'application.my.widgets.wijmo',
        'my'=>'application.my',
        'user'=>'application.modules.user',
        'friend'=>'application.modules.friend',
    ),

);