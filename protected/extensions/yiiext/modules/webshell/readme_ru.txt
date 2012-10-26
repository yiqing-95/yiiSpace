Yii web shell
=============
Веб-консоль для Yii. Позволяет запускать консольные команды из браузера. Полезна на
серверах без SSH и в админках консольного стиля.

Для работы используется та же конфигурация, что и у основного приложения,
то есть если работает приложение, будет работать и консоль.

Установка
---------

Для использования веб-консоли необходимо подключить модуль в настройках
приложения:

~~~
[php]
return array(
    …
    'modules'=>array(
        'webshell'=>array(
            'class'=>'ext.yiiext.modules.webshell.WebShellModule',
            // URL, на который будет редирект после ввода 'exit'
            'exitUrl' => '/',
            // опции для wterm
            'wtermOptions' => array(
                // строка запроса в стиле linux
                'PS1' => '%',
            ),
            // дополнительные команды (см. далее)
            'commands' => array(
                'test' => array('js:function(){return "Hello, world!";}', 'Just a test.'),
            ),
            // раскомментировать для отключения yiic
            // 'useYiic' => false,

            // добавляем команды yiic из нестандартных директорий
            'yiicCommandMap' => array(
                'email'=>array(
	                'class'=>'ext.mailer.MailerCommand',
	                'from'=>'sam@rmcreative.ru',
	            ),
            ),
        ),
    ),
)
~~~

Консоль будет доступна по адресу:

http://localhost/path/to/index.php?r=webshell

Если используются красивые URL, и написаны свои правила роутинга, может понадобится
добавить несколько правил для веб-консоли:

~~~
[php]
'components'=>array(
    'urlManager'=>array(
        'urlFormat'=>'path',
            'rules'=>array(
                'webshell'=>'webshell',
                'webshell/<controller:\w+>'=>'webshell/<controller>',
                'webshell/<controller:\w+>/<action:\w+>'=>'webshell/<controller>/<action>',
                …остальне правила…
        ),
    )
)
~~~

После этого можно будет обращаться к веб-консоли так:

http://localhost/path_to_webroot/webshell

Добавляем свои команды
----------------------

К веб-консоли можно добавить как общие команды, так и команды yiic.

Общие команды добавляются чере свойство commands модуля `WebShellModule`:

~~~
[php]
'commands' => array(
    // js callback в качестве команды
    'test' => array('js:function(tokens){return "Hello, world!";}', 'Just a test.'),

    // ajax-команда с URL http://yourwebsite/post/index?action=cli (при наличии правил маршрутизации URL будет изменён соответственно)
    'postlist' => array(array('/post/index', array('action' => 'cli')), 'Описание.'),

    // «залипающая» команда. Для выхода из неё надо набрать 'exit'.
    'stickyhandler' => array(
        array(
            // необязательно: вызывается при наборе 'stickyhandler'. Может быть как URL, так и callback JavaScript.
            'START_HOOK' => array('/post/index', array('action' => 'start')),
            // необязательно: вызывается при наборе 'exit'. Может быть как URL, так и callback JavaScript.
            'EXIT_HOOK' => "js:function(){ return 'bye!'; }",
            // требуется: cвызывается при наборе параметра. Может быть как URL, так и callback JavaScript.
            'DISPATCH' => "js:function(tokens){ return "Hi, Jack!"; }",
            // необязательно: своя строка запроса
            'PS1' => 'advanced >',
        ),
        'Advanced command.',
    ),
),
~~~

Обработчик общей команды должен выглядеть примерно так:

~~~
[php]
function actionMyCommandHandler(){
    $tokens = explode(" ", $_GET['tokens']);
    print_r($tokens);
}
~~~

Создание команд yiic описано в руководстве в разделе «[Консольные приложения](http://yiiframework.ru/doc/guide/ru/topics.console)».

Безопасность
------------
Для обеспечения безопасности консоли в модуле есть два свойства:

~~~
[php]
// IP, с которых доступна консоль. По умолчанию доступен только запуск с локальной машины. Для доступа со всех IP выставить в false.
'ipFilters' => array('127.0.0.1','::1'),
// PHP callback, возвращающий true/false и определяющий, есть ли доступ к веб-консоли.
// Привер приведён для PHP 5.3.
'checkAccessCallback' => function($controller, $action){
    return !Yii::app()->user->isGuest;
}
~~~


Большое спасибо
---------------
- [Dimitrios Meggidis](http://www.yiiframework.com/forum/index.php?/user/4786-tydeas-dr/) за
отличную идею и за то, что показал wterm.
Кстати, у него есть [неплохой виджет для Yii, позволяющий использовать wterm](http://github.com/dmtrs/EWebTerm).

- [Qiang Xue](http://www.yiiframework.com/about/) за Yii и код ipFilters.
