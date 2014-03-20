<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-10
 * Time: 下午7:01
 * To change this template use File | Settings | File Templates.
 */
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'theme'=>'sns_new',
        // Put front-end settings there
        // (for example, url rules).
            // Put back-end settings there.
            'components'=>array(
                // uncomment the following to enable URLs in path-format
                'urlManager' => array(
                    'urlFormat' => 'path',
                    //'caseSensitive'=>false,
                     'showScriptName' => false, //隐藏index.php  要配合  服务器重写 将所有请求导航到 index.php上
                    'rules' => array(
                        //'<controller:\w+>/<id:\d+>' => '<controller>/views',
                        //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                        //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                        //'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                    ),
                ),
            ),
    )
);