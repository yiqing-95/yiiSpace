Forgery Session Filter
======================

EForgerySessionFilter performs load session with id which sent with POST or GET parameter.

Installing and configuring your controller
------------------------------------------
~~~
[php]
public function filters()
{
	return array(
		array(
			'ext.yiiext.filters.forgerySession.EForgerySessionFilter[ +|- Action1, Action2, ...]',
			// the name of the parameter that stores session id.
			'paramName'=>'PHP_SESSION_ID',
			// the method which sent the data. This should be either 'POST', 'GET' or 'AUTO'.
			'method'=>'POST',
		),
	);
}
~~~

Notes
-----
This filter should be run before accessControl filter.