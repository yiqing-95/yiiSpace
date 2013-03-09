<?php
/**
 * 远程client端的对等体
 * 位于：
 * application.modules.test.services.TestServiceHolder
 */
$alias = md5(__FILE__);
Yii::setPathOfAlias($alias,dirname(__FILE__));
Yii::import($alias.'.interfaces.ITestService');

class TestServiceHolder
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

    /**
     * @return ITestService
     */
    public function getTest2Service(){
        Yii::import('application.vendors.json_rpc.jsonRPCClient');

        //echo Yii::app()->createAbsoluteUrl('/api/jsonRpc',array('module'=>'test','method'=>__FUNCTION__) );
        // the url will be different ,here is just a example
        //$serviceRemoteProxy = new jsonRPCClient(Yii::app()->createAbsoluteUrl('/api/jsonRpc',array('module'=>'test','method'=>__FUNCTION__)), true);
        $serviceRemoteProxy = new jsonRPCClient(Yii::app()->createAbsoluteUrl('/api/jsonRpc',array('module'=>'test','method'=>__FUNCTION__)),false);
        //$serviceRemoteProxy = new jsonRPCClient($this->createAbsoluteUrl('/api/rpc'));
        return $serviceRemoteProxy ; // fake as a instance of ITestService then you can use Intelligent completion from the IDE

    }

}
