<?php
/**
 * User: yiqing
 * Date: 13-1-21
 * Time: 上午10:28
 * change template => | Settings | File Templates.
 * ------------------------------------------------
 * ------------------------------------------------
 */
$alias = md5(__FILE__);
Yii::setPathOfAlias($alias,dirname(__FILE__));
Yii::import($alias.'.interfaces.ITestService');
/**
 * 叫啥名呢？ 或者TestServicePool
 * 可参考的设计模式有： xxxFactory,facade.
 * IOC(控制反转/依赖注入--通过配置文件加载特定的服务类)
 */
class AppServiceHolder
{
    //.............<<单体设计模式..............................................
    private static $_instance;

    private function __construct()
    {
        // parent::__construct();
    }

    protected function __clone(){
        parent::__clone();
    }

    /**
     * @static
     * @return TestServiceHolder
     */
    static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    //.............单体设计模式>>..............................................

    //===========================================================================
    /**
     * 下面用来写返回的服务类方法
     */
    /**
     * @return IAppService
     * 如果在远程的对等体调用时 返回的将是不同的 route
     * 根据 route 来实例化不同的service而已
     */
    public function getTest2Service(){
        return new AppService();
    }



}
