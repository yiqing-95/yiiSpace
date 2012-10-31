<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-10
 * Time: 下午12:16
 * To change this template use File | Settings | File Templates.
 * ---------------------------------------------------------------
 * 网站首页内容板块总代理控制器 用来请求各个module提供的内容块
 * 多用ajax加载 所以不需要视图 统一在这里解析就好 各个内容块以widget形式
 * 提供
 * ---------------------------------------------------------------
 * 各个module如果要为首页提供内容 只需要配置controllerMap 和实现自己
 * 的PageBlock widget 即可！
 * ---------------------------------------------------------------
 *
 */
class HomeBlockController extends Controller
{
    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('*'),
                'actions' => array($this->action->id),
            ),
        );
    }

    public function actionBox(){

    }
}
