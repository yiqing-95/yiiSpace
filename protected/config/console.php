<?php
$consoleConfigs = dirname(__FILE__). DIRECTORY_SEPARATOR. 'console';

return   array(
        'language' => 'en',
         'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
         'name'=>'My Console Application',
        // preloading 'log' component

        'components'=>array(

           'db'=>array(
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
         ),

        'modules' => array(
            #...
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
                'returnUrl' => array('/user/profile'),

                # page after logout
                'returnLogoutUrl' => array('/user/login'),
            ),
            #...
        ),
        /*
         'preload' => require(dirname(__FILE__) . '/console/preloads.php'),

         // autoloading model and component classes
         'import' => require(dirname(__FILE__) . '/console/imports.php'),

         // application components
         'components' => require(dirname(__FILE__) . '/console/components.php'),

         'modules' => require(dirname(__FILE__) . '/console/modules.php'),

         'params' => require(dirname(__FILE__) . '/console/params.php'),

         'behaviors' => require(dirname(__FILE__) . '/console/behaviors.php'),
         */
    );
