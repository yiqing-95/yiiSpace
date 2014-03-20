Forgery Session Filter
======================

EForgerySessionFilter загружает сессию, ИД которой передан через POST или GET параметр.

Установка и настройка
---------------------
~~~
[php]
public function filters()
{
	return array(
		array(
			'ext.yiiext.filters.forgerySession.EForgerySessionFilter[ +|- Action1, Action2, ...]',
			// имя параметра где указан ИД сессии
			'paramName'=>'PHP_SESSION_ID',
			// метод которым переданы данные
			'method'=>'POST',
		),
	);
}
~~~

Дополнение
----------
Это фильтр нужно запускать до accessControl фильтра.