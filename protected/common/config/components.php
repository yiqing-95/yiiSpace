<?php

// set the smtp config for swiftMailer
$smtpConfig = getSmtpConfig();


// application components
return array(

    //................{yii standard components}....................................................................
    //  use  MySQL database
    'db' => array(
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=localhost;dbname=yii_space',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'emulatePrepare' => true,
        'enableParamLogging' => 1,
        'enableProfiling' => 1,
        //'schemaCachingDuration' => 108000,
        'tablePrefix' => '',
    ),

    'errorHandler' => array(
        // use 'site/error' action to display errors
        'errorAction' => 'site/error',
    ),

    'log' => array(
        'class' => 'CLogRouter',
        'routes' => array(
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning ,info',
            ),
           /*
            array(
                'class' => 'application.extensions.pqp.PQPLogRoute',
                'categories' => 'application.*, exception.*',
            ),*/
            array(
                'class'=>'ext.db_profiler.DbProfileLogRoute',
                'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
                'slowQueryMin' => 0.01, // Minimum time for the query to be slow
            ),
            /* 这个在foundation扩展中有问题！注释掉就好了
            array(
                'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                'ipFilters' => array('*'),
            ),
            */
        ),
    ),

    //................{yii standard components /}....................................................................

    //................{ components  special for this app}....................................................................

    'uploadStorage' => array(
        'class' => 'application.components.LUploadStorage',
    ),

    #模块url规则重写
    'modUrlRuleManager' => array(
        'class' => 'application.common.components.ModuleUrlRuleManager',
    ),


    //总是可用的文件cache
    'fileCache' => array(
        'class' => 'system.caching.CFileCache',
    ),

    //public Assets 用来访问网站全局公共资源（js ，css ，images）
    'publicAssets' => array(
        'class' => 'application.components.PublicAssets',
    ),
    //................{ components  special for this app}....................................................................

    //............{extension from yii repo }................................................................

    'settings'=>array(
        'class'                 => 'ext.CmsSettings',
        'cacheComponentId'  => 'cache',
        'cacheId'           => 'global_website_settings',
        'cacheTime'         => 84000,
        'tableName'     => '{{settings}}',
        'dbComponentId'     => 'db',
        'createTable'       => true,
        'dbEngine'      => 'InnoDB',
    ),


    'foundation' => array(
        "class" => "ext.foundation.components.Foundation"
    ),

    //互斥锁
    'mutex' => array(
        'class' => 'application.extensions.EMutex',
    ),

    //yii-mail
    'mail' => array(
        'class' => 'ext.yii-mail.YiiMail',
        'transportType' => 'smtp',
        'transportOptions'=>array(
        'host'=>$smtpConfig['host'],
            'username'=>$smtpConfig['username'], //yii_qing@163.com
            'password'=>$smtpConfig['password'],         //yiqing
            'port'=>$smtpConfig['port'],
    ),
        'viewPath' => 'application.views.mail',
        'logging' => true,
        'dryRun' => false
    ),

    // the style yii extension
    'syte' => array(
        'class' => 'application.modules.syte.components.SyteApplicationComponents',
    ),

    'extensionLoader'=>array(
        'class' => 'ExtensionLoader',
    ),
    //............{extension from yii repo /}................................................................
);
/**
 * @return array
 */
function getSmtpConfig(){
    $options = array(
        array(
            'host'=>'smtp.163.com',
            'username'=>'yii_qing@163.com', // notice $msg->from must be this too!
            'password'=>'yiqing',
            'port'=>25
        ),
        /*
        array(
            'host'=>'smtp.163.com',
            'username'=>'livmx95@163.com',
            'password'=>'yiqing95',
            'port'=>25
        ),*/
    );
    //shuffle($options);
    return $options[array_rand($options)];
}