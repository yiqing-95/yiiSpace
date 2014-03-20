GetUrl Behavior
===============

Application behavior for generating URLs.

Installation
------------

~~~
[php]
// Install behavior into application config (/config/main.php)
return array(
	// ...
	'behaviors'=>array(
		'home'=>array(
			'class'=>'ext.yiiext.behaviors.getUrl.EGetUrlBehavior',
			// whether returned url be absolute. Defaults to false.
			'useAbsolute'=>true,
		),
		'publicFilesUrl'=>array(
			'class'=>'ext.yiiext.behaviors.getUrl.EGetUrlBehavior',
			'useAbsolute'=>true,
			// the base url for files (without slashes)
			'baseUrl'=>'public',
		),
	),
	// ...
);
~~~

Usage
-----

~~~
[php]
echo '1. '.Yii::app()->home;
echo '2. '.Yii::app()->home->getUrl('favicon.ico');
echo '3. '.Yii::app()->getUrl('favicon.ico');
echo '4. '.Yii::app()->home->getUrl('css/main.css');
echo '5. '.Yii::app()->publicFilesUrl;
echo '6. '.Yii::app()->publicFilesUrl->getUrl('logo.png');
~~~

Result
------

1. http://localhost/
2. http://localhost/favicon.ico
3. http://localhost/favicon.ico
4. http://localhost/css/main.css
5. http://localhost/public/
6. http://localhost/public/logo.png
