GetUrl Behavior
===============

Поведение для приложения для генерации ссылка на файл.

Установка
-------------
~~~
[php]
// Добавляем поведение для приложения в настройках (/config/main.php)
return array(
	// ...
	'behaviors'=>array(
		'home'=>array(
			'class'=>'ext.yiiext.behaviors.getUrl.EGetUrlBehavior',
			// хотим получать абсолютные пути. По умолчанию false.
			'useAbsolute'=>true,
		),
		'publicFilesUrl'=>array(
			'class'=>'ext.yiiext.behaviors.getUrl.EGetUrlBehavior',
			'useAbsolute'=>true,
			// устанавливаем базовый относительный путь (без крайних слешей)
			'baseUrl'=>'public',
		),
	),
	// ...
);
~~~

Использование
-------------
~~~
[php]
echo '1. '.Yii::app()->home;
echo '2. '.Yii::app()->home->getUrl('favicon.ico');
echo '3. '.Yii::app()->getUrl('favicon.ico');
echo '4. '.Yii::app()->home->getUrl('css/main.css');
echo '5. '.Yii::app()->publicFilesUrl;
echo '6. '.Yii::app()->publicFilesUrl->getUrl('logo.png');
~~~

Результат
-------------
1. http://localhost/
2. http://localhost/favicon.ico
3. http://localhost/favicon.ico
4. http://localhost/css/main.css
5. http://localhost/public/
6. http://localhost/public/logo.png
