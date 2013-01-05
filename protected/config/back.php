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
return CMap::mergeArray(
    $mainConfig,
    array(
        //  'theme'=>'abound',
        // Put back-end settings there.
        'components'=>array(
            // uncomment the following to enable URLs in path-format
            'urlManager' => array(
                'urlFormat' => 'path',
                //'caseSensitive'=>false,
                // 'showScriptName' => false, //隐藏index.php  要配合  服务器重写 将所有请求导航到 index.php上 后台不能隐藏
                'rules' => array(
                    //'<controller:\w+>/<id:\d+>' => '<controller>/views',
                    //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                    //'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                ),
            ),
            'user' => array(
                // 'class' => 'RWebUser',
                // enable cookie-based authentication
                'allowAutoLogin' => true,
                'stateKeyPrefix'=>'admin',
            ),
        ),
        'params'=>array(
            'layout'=>'//adminLayouts/main',
        )
    )
);