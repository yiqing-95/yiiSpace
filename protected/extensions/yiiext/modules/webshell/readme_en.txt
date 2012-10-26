Yii web shell
=============
Web shell for Yii allows you to run console commands from your browser. Can be useful for both no-ssh webservers and
console-style administration modules.

Yii web shell uses the same config as your web application so if your application works it will work too.

Installing
----------

To use web shell, you must include it as a module in the application configuration like the following:

~~~
[php]
return array(
    …
    'modules'=>array(
        'webshell'=>array(
            'class'=>'ext.yiiext.modules.webshell.WebShellModule',
            // when typing 'exit', user will be redirected to this URL
            'exitUrl' => '/',
            // custom wterm options
            'wtermOptions' => array(
                // linux-like command prompt
                'PS1' => '%',
            ),
            // additional commands (see below)
            'commands' => array(
                'test' => array('js:function(){return "Hello, world!";}', 'Just a test.'),
            ),
            // uncomment to disable yiic
            // 'useYiic' => false,

            // adding custom yiic commands not from protected/commands dir
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

With the above configuration, you will be able to access web shell in your browser using
the following URL:

http://localhost/path/to/index.php?r=webshell

If your application is using path-format URLs with some customized URL rules, you may need to add
the following URLs in your application configuration in order to access web shell module:

~~~
[php]
'components'=>array(
    'urlManager'=>array(
        'urlFormat'=>'path',
            'rules'=>array(
                'webshell'=>'webshell',
                'webshell/<controller:\w+>'=>'webshell/<controller>',
                'webshell/<controller:\w+>/<action:\w+>'=>'webshell/<controller>/<action>',
                …other rules…
        ),
    )
)
~~~

You can then access web shell via:

http://localhost/path_to_webroot/webshell

Adding custom commands
----------------------

You can add both shell commands and yiic commands.

Shell commands are configured via commands property of `WebShellModule`:

~~~
[php]
'commands' => array(
    // js callback as a command
    'test' => array('js:function(tokens){return "Hello, world!";}', 'Just a test.'),

    // ajax callback to http://yourwebsite/post/index?action=cli (will be normalized according to URL rules)
    'postlist' => array(array('/post/index', array('action' => 'cli')), 'Description.'),

    // sticky command handler. One will need to type 'exit' to leave its context.
    'stickyhandler' => array(
        array(
            // optional: called when 'stickyhandler' is typed. Can be either URL array or callback.
            'START_HOOK' => array('/post/index', array('action' => 'start')),
            // optional: called when 'exit' is typed. Can be either URL array or callback.
            'EXIT_HOOK' => "js:function(){ return 'bye!'; }",
            // required: called when parameter is typed. Can be either URL array or callback.
            'DISPATCH' => "js:function(tokens){ return "Hi, Jack!"; }",
            // optional: custom prompt
            'PS1' => 'advanced >',
        ),
        'Advanced command.',
    ),
),
~~~

Callback for a shell command should look like this:

~~~
[php]
function actionMyCommandHandler(){
    $tokens = explode(" ", $_GET['tokens']);
    print_r($tokens);
}
~~~

To learn about creating custom yiic commands you can read "[Console Applications](http://www.yiiframework.com/doc/guide/topics.console)".

Security
--------
There are two module settings that will help you to keep web console secure:

~~~
[php]
// Allowed IPs, localhost by default. Set to false to allow all IPs.
'ipFilters' => array('127.0.0.1','::1'),
// Valid PHP callback that returns if user should be allowed to use web shell.
// In this example it's valid for PHP 5.3.
'checkAccessCallback' => function($controller, $action){
    return !Yii::app()->user->isGuest;
}
~~~


Special thanks
--------------
- [Dimitrios Meggidis](http://www.yiiframework.com/forum/index.php?/user/4786-tydeas-dr/) for
a nice idea and for showing me wterm.
You can check his [general purpose wterm Yii widget](http://github.com/dmtrs/EWebTerm).

- [Qiang Xue](http://www.yiiframework.com/about/) for Yii itself, and ipFilters routine code.
