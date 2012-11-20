<?php

// application components
return array(

    //................{yii standard components}....................................................................
    'session' => array(
        'class' => 'application.my.components.YsDbHttpSession',
        'sessionName' => 'yiiSpace',
        'connectionID'=>'db',  // important !! if not set will use sqliteDb as storage .
       // 'gCProbability'=>4  , // every 4 request will invoke the Session  GC 测试打开之
       // 'timeout'=>120 ,
    ),

    /* uncomment the following to enable URLs in path-format
    'urlManager' => array(
        'urlFormat' => 'path',
        //'caseSensitive'=>false,
        'showScriptName' => false, //隐藏index.php  要配合  服务器重写 将所有请求导航到 index.php上
        'rules' => array(
            //'<controller:\w+>/<id:\d+>' => '<controller>/views',
            //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            //'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
        ),
    ),*/

    // 云存储时的情况 参考： https://github.com/andremetzen/yii-s3assetmanager
    /**
     * 通过该例子 看出 凡是可以设置baseUrl的组件 都可以指到某个指定的域名去！！
     * 比如用bu() 方法得到的图像地址 如果request 组件的baseUrl是全域名路径 那么
     * 图像地址会变成全域名情况 所以在类似图像地址输出上尽量用bu方法 这样如果是
     * 多台服务器都有图像那么.....
     */
    'assetManager'=>array(
     // 'baseUrl'=>'http:://www.xx.com'  多web应用时可以指定一个服务器 cdn??
   ),

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

    'user' => array(
         'class' => 'application.my.components.YsWebUser',
        // enable cookie-based authentication
        'allowAutoLogin' => true,
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
                'levels' => 'error,warning ,info',// 'error,warning ,info,trace',
            ),

            array(
                'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                'ipFilters' => array('*'),
            ),
        ),
    ),

    'cache'=>array(
        'class'=>'system.caching.CFileCache',
    ),

    'messages' => array (
        'class'=>'application.components.ExPhpMessageSource',
        // Pending on core: http://code.google.com/p/yii/issues/detail?id=2624
        'extensionBasePaths' => array(
            'giix' => 'ext.giix.messages', // giix messages directory.
        ),
    ),
    //................{yii standard components /}....................................................................

    //................{ components  special for this app}....................................................................

    'uploadStorage' => array(
        'class' => 'application.components.LUploadStorage',
    ),

    #模块url规则重写
    'modUrlRuleManager' => array(
        'class' => 'application.my.components.ModuleUrlRuleManager',
    ),


    //总是可用的文件cache
    'fileCache' => array(
        'class' => 'system.caching.CFileCache',
    ),

    //public Assets 用来访问网站全局公共资源（js ，css ，images）
    'publicAssets' => array(
        'class' => 'application.components.PublicAssets',
    ),


    # 文件操作对象的组件
    'file' => array(
        'class' => 'ext.file.CFile'
    ),

    'uploadStorage'=>array(
      'class'=>'application.components.YsUploadStorage'
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
       // 'createTable'       => true,
        'dbEngine'      => 'InnoDB',
    ),


    'bootstrap' => array(
        "class" => "ext.YiiBooster.components.Bootstrap"
    ),

    //互斥锁
    'mutex' => array(
        'class' => 'application.extensions.EMutex',
    ),

    'image'=>array(
        'class'=>'application.extensions.image.CImageComponent',
        // GD or ImageMagick
        'driver'=>'GD',
        // ImageMagick setup path
        // 'params'=>array('directory'=>'/opt/local/bin'),
    ),


    //............{extension from yii repo /}................................................................
);