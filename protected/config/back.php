<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-10
 * Time: 下午7:02
 * To change this template use File | Settings | File Templates.
 */
$mainConfig = require(dirname(__FILE__).'/main.php');
//unset($mainConfig['modules']['test']);
unset($mainConfig['components']['modUrlRuleManager']);

//print_r($mainConfig);
return CMap::mergeArray(
    $mainConfig,
    array(
        // 'theme'=>'EzBoot',
        // Put back-end settings there.
        'components'=>array(
            // uncomment the following to enable URLs in path-format
            'urlManager' => array(
                'urlFormat' => 'path',
                //'caseSensitive'=>false,
                // 'showScriptName' => false, //隐藏index.php  要配合  服务器重写 将所有请求导航到 index.php上 后台不能隐藏
                'rules' => array(
                    //'<controller:\w+>/<id:\d+>' => '<controller>/views',
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                    '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                ),
            ),
            /*
             * https://github.com/yiisoft/yii2/issues/1578
             *
            'frontUrlManager'=>array(
              'class'=>'CUrlManager',

            ),
            */
            'user' => array(
                 'class' => 'AdminWebUser',
                // enable cookie-based authentication
                'allowAutoLogin' => true,
                'stateKeyPrefix'=>'admin',
            ),

            'bootstrap' => array(
                "class" => "ext.YiiBooster.components.Bootstrap",
                //'coreCss'=>false,
            ),
        ),
        'params'=>array(
            'layout'=>'//adminLayouts/main',
        ),
        'import'=>array(
            'application.modules.backend.components.*',
            'application.modules.backend.models.*',
        ),
           'preload' =>array(
               'log',
               'bootstrap',
              // 'modUrlRuleManager',
           )
    )
);