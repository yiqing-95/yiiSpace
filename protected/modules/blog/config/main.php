<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
	'defaultController'=>'post',
    'theme'=>'classic',     //皮肤配置 default为默认或注释掉
	// preloading 'log' component
        'preload'=>array(
			'log',
			//'bootstrap',
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

        'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			// 'generatorPaths'=>array(
           	 	// 'bootstrap.gii', // since 0.9.1
        	// ),
		),

	),

	// application components
	'components'=>array(
		'assetManager'=>array(
			'newDirMode'=>0755,
			'newFileMode'=>0644,	
		),	
		
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
        'request'=>array(
        	//CSRF防范
            'enableCsrfValidation'=>true,
            // Cookie攻击的防范
            'enableCookieValidation'=>true,
        ),
            
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName' => false,
			'rules'=>array(
				'view/<controller:\w+>-<title:.*?>-<id:\d+>'=>'<controller>/view',
                'tags/<tag:.*?>'=>'post/index',
                'category/<alias:.*?>-<category:.*?>'=>'post/index',
                'date/<year:\d+>-<month:\d+>'=>'post/index',
                '/'=>'post/index', //使用home
			),
		),
            
		// uncomment the following to use a MySQL database
		'db'=>require(dirname(__FILE__).'/dlfdb.php'),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
                 'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
