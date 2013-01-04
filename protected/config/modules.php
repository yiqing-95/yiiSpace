<?php

return array(
    //模块 后面的数组会导致模块的同名公共变量被调用 所以可在这里完成配置相关的工作
    // uncomment the following to enable the Gii tool
    'gii' => array(
        'class' => 'system.gii.GiiModule', // 去掉注释用系统的  填上注释用自己改装的gii
        'password' => 'yiqing',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        'ipFilters' => array('127.0.0.1', '::1'),
        'generatorPaths' => array(
            // 顺序也可能影响 有些模板不能加载哦！
            'my.gii',
            'ext.giix-core', // giix generators

            'ext.YiiBooster.gii'
        ),
    ),

    //用户模块
    'user' => array(
        # encrypting method (php hash function)
        'hash' => 'md5',

        # send activation email
        'sendActivationMail' => true,

        # allow access for non-activated users
        'loginNotActiv' => false,

        # activate user on registration (only sendActivationMail = false)
        'activeAfterRegister' => false,

        # automatically login from registration
        'autoLogin' => true,

        # registration path
        'registrationUrl' => array('/user/registration'),

        # recovery password path
        'recoveryUrl' => array('/user/recovery'),

        # login form path
        'loginUrl' => array('/user/login'),

        # page after login
        'returnUrl' => array('/user/home'),

        # page after logout
        'returnLogoutUrl' => array('/user/login'),
    ),

    //webshell
    'webshell' => array(
        'class' => 'ext.yiiext.modules.webshell.WebShellModule',
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
            'queryii' => array(
                'class' => 'application.commands.shell.QueryiiCommand',
            ),
        ),
    ),

    /**
     *
     */
    'backup'=>array(

        'path'=>  dirname(__FILE__) .'/../data/backup/',
    ),

    'blog',

    //dashboard module
    'sdashboard' => array(),


    'friend',

    'backend',

    'msg',

    'status',

    'group',

    //'test',

    'photo',
    //-----------------------<for comments >-----------------------------------------------------------------
    'comments' => array(
        //you may override default config for all connecting models
        'defaultModelConfig' => array(
            //only registered users can post comments
            'registeredOnly' => false,
            'useCaptcha' => false,
            //allow comment tree
            'allowSubcommenting' => true,
            //display comments after moderation
            'premoderate' => false,
            //action for postig comment
            'postCommentAction' => 'comments/comment/postComment',
            //super user condition(display comment list in admin view and automoderate comments)
            'isSuperuser' => '1==1',//'Yii::app()->user->checkAccess("moderate")',
            //order direction for comments
            'orderComments' => 'DESC',
        ),
        //the models for commenting
        'commentableModels' => array(
            /*
            //model with individual settings
            'User' => array(
                'registeredOnly' => true,
                'useCaptcha' => false,
                'allowSubcommenting' => true,
            ),
            //model with default settings
            'Photo',
            */
        ),
        //config for user models, which is used in application
        'userConfig' => array(
            'class' => 'User',
            'nameProperty' => 'username',
            'emailProperty' => 'email',
        ),
    ),
    //-----------------------<for comments />-----------------------------------------------------------------

    'voting',
);

