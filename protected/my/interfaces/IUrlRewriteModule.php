<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-4-8
 * Time: 上午11:14
 *------------------------------------------------------------
 *                 _            _
 *                (_)          (_)
 *        _   __  __   .--. _  __   _ .--.   .--./)
 *       [ \ [  ][  |/ /'`\' ][  | [ `.-. | / /'`\;
 *        \ '/ /  | || \__/ |  | |  | | | | \ \._//
 *      [\_:  /  [___]\__.; | [___][___||__].',__`
 *       \__.'            |__]             ( ( __))
 *
 *--------------------------------------------------------------
 *  module可以实现之 用来重写module的url访问规则
 * 如：
 * user/user/login  =>  user/login
 *
 *       '<module:\w+><controller:\w+>/<id:\d+>'=>'<module><controller>/view',
 *          '<module:\w+><controller:\w+>/<action:\w+>/<id:\d+>'=>'<module><controller>/<action>',
 *         '<module:\w+><controller:\w+>/<action:\w+>'=>'<module><controller>/<action>',
 *       '<controller:\w+>/<id:\d+>'=>'<controller>/view',
 *       '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
 *      '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
 * ------------------------------------------------------------
 * 处理类：my.components.ModuleUrlRuleManager
 * -------------------------------------------------------------
 * array('app/get',
 *       'pattern' => 'apps(/<app:[\w\d\.]+>)?(/updates/<update:\d+>)?(/<revision:published|draft>)?',
 *       'verb' => 'GET'
 *   ),
 *@see http://www.yiiframework.com/forum/index.php/topic/35616-yii-url-rules-optional-parameters/page__pid__179679#entry179679
 *
 * -------------------------------------------------------------
 */
interface IUrlRewriteModule
{

    /**
     * Method to return urlManager-parseable url rules
     * @return array An array of urlRules for this object
     * -------------------------------------------------------
     * return array(

     *  );
     *----------------------------------------------------------
     * 常用规则：
     * 模块名和控制器同名：'forum/<action:\w+>'=>'forum/forum/<action>',
     *
     *----------------------------------------------------------
     */
    public static function getUrlRules();
}
