<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-24
 * Time: 下午9:28
 * To change this template use File | Settings | File Templates.
 * -----------------------------------------------------ix-----------------
 * 关于json_rpc   请参考：http://json-rpc.org/wiki/implementations
 * 以及本项目文件夹：doc/misc/codeReference/zabbix_php_api_v1_0
 * ................................................................
 * 另外常见的远程调用还有soap和xmlRpc
 * phpPrc用php序列化传输（只能用在都是php开发的应用间通讯）
 * ................................................................
 * fastRpc 也可以跨应用跨语言通讯 配合serviceProxy在多个大型项目中被采用
 * 请参考 [服务化的网站架构](http://www.slideshare.net/thinkinlamp/ss-6168750)
 * ----------------------------------------------------------------------
 * 2014年：
 * 远程rpc的实现 在module中的实现：
 * 将仿形进行到底 每个模块也提供api/rpc 实现[moduleId]/api/rpc
 * ...............................
 * 还有一种实现通过Yii::app()->runController  实现内部调用 用jsonRpc实现外部调用
 *
 * 参考symfony相关概念：
 * the format
 * BUNDLE_NAME:CONTROLLER_NAME:ACTION_NAME. For instance, AcmeDemoBundle:Welcome:index maps to
 *  the indexAction method from the Acme\DemoBundle\Controller\WelcomeController class
 * 对外是逻辑名称 对内需要映射到物理名称上
 * ................................
 * YsService::instance()->call('<moduleId>','serviceName:methodName',array(....))
 */
class YsService extends CApplicationComponent
{

    /**
     * @static
     * @return YsService
     */
    public static function instance()
    {
        if (!isset(Yii::app()->service)) {

            Yii::app()->setComponents(array(
                'service' => array(
                    'class' => __CLASS__,
                   // 'mode' => self::MODE_LOCAL,
                ),
            ), false);
        }
        return Yii::app()->service;
    }

    const MODE_JSON_RPC = 'json_rpc';
    const MODE_XML_RPC = 'xml_rpc';
    /**
     * @see http://www.phprpc.org/zh_CN/
     */
    const MODE_PHP_RPC = 'php_rpc';
    const MODE_LOCAL = 'local';
    /**
     * @see http://code.google.com/p/fastcgirpc/
     */
    const MODE_FAST_RPC = 'fast_rpc';

    /**
     * @static
     * @param string $moduleId
     * @param string $serviceName
     * @param array $params
     * @return mixed|void
     */
    static public function call($moduleId = '', $serviceName = '', $params = array())
    {
         return self::instance()->callModuleService($moduleId,$serviceName,$params);
    }

    public $mode = self::MODE_LOCAL;

    protected $modulePath = array();


    public function callModuleService($moduleId = '', $serviceName = '', $params = array())
    {

        if ($this->mode = self::MODE_LOCAL) {
            return $this->callLocalModuleService($moduleId, $serviceName, $params);
        } elseif ($this->mode = self::MODE_JSON_RPC) {
            return $this->callJsonRpcModuleService($moduleId,$serviceName,$params);
        } else {
            throw new CException("the {$this->model} is not supported yet");
        }
    }

    /**
     * @param string $moduleId
     * @param string $serviceName
     * @param array $params
     * @return mixed
     */
    protected function callLocalModuleService($moduleId = '', $serviceName = '', $params = array())
    {
        $modulePath = $this->getModulePath($moduleId);
        if (($pos = strpos($serviceName, '.')) !== false) {
            $serviceClassName = ucfirst(substr($serviceName, 0, $pos)) . "Service";
            $serviceMethodName = substr($serviceName, $pos + 1);
        } else {
            $serviceClassName = ucfirst($moduleId) . "Service";
            $serviceMethodName = $serviceName;
        }
        $servicePath = $modulePath . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . "{$serviceClassName}.php";

        require_once($servicePath);

        $serviceObj = new $serviceClassName();
        // setMode
        if($serviceObj instanceof IModuleService){
            $serviceObj->setProcessMode($this->mode);
        }
        return call_user_func_array(array($serviceObj, $serviceMethodName), $params);
    }

    /**
     * @param $moduleId 目前只支持平面化module 不支持更深层次的module嵌套了
     * @return string
     * 如果通过moduleId 去找模块的路径 影响单元测试的 很麻烦
     * 所以模块下的service 不要通过 module本身来提供 dolphin是module自身提供serviceXXX方法的
     */
    protected function getModulePath($moduleId = '')
    {
        // return  dirname(Yii::app()->getModule($moduleId)->getModulePath());
        $modulePath = Yii::app()->getModulePath();
        return $modulePath . DIRECTORY_SEPARATOR . $moduleId;
    }

    protected function callJsonRpcModuleService($moduleId = '', $serviceName = '', $params = array())
    {
        Yii::import('application.vendors.json_rpc.jsonRPCClient');
        //$serviceRemoteProxy = new jsonRPCClient($this->createAbsoluteUrl('/api/rpc'),true);
        $serviceRemoteProxy = new jsonRPCClient($this->createAbsoluteUrl('/api/rpc'));

        try {
            echo $serviceRemoteProxy->callModuleService($moduleId,$serviceName,$params);

        } catch (Exception $e) {
            echo nl2br($e->getMessage()).'<br />'."\n";
        }
    }

}
