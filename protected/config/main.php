<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

$frontend = dirname(dirname(__FILE__));

Yii::setPathOfAlias('common', $frontend . DIRECTORY_SEPARATOR . 'common');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'YiiSpace',
    'language' => 'zh_cn',
    'charset' => "UTF-8",

    // autoloading model and component classes
    'import' => CMap::mergeArray( require($frontend . '/common/config/imports.php') ,require(dirname(__FILE__) . '/imports.php')),
    // application components
    'components' =>  CMap::mergeArray( require($frontend . '/common/config/components.php') ,require(dirname(__FILE__) . '/components.php')),
    // preloading 'log' component
    'preload' => require(dirname(__FILE__) . '/preloads.php'),

    'modules' => require(dirname(__FILE__) . '/modules.php'),

    'params' => require(dirname(__FILE__) . '/params.php'),

    'behaviors' => require(dirname(__FILE__) . '/behaviors.php'),

    'controllerMap' =>  CMap::mergeArray( require($frontend . '/common/config/controllerMaps.php') ,require(dirname(__FILE__) . '/controllerMaps.php')),

    'aliases' => array(
        //assuming you extracted the files to the extensions folder
        //  'xupload' => 'ext.xupload',
        'wij' => 'application.my.widgets.wijmo',
        'my'=>'application.my',
        'user'=>'application.modules.user',
        'friend'=>'application.modules.friend',
    ),

);