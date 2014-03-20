<?php
/**
 *  参考YsService的实现 现在统一改为通过控制器来实现
 *
 * 2014年：
 * 远程rpc的实现 在module中的实现：
 * 将仿形进行到底 每个模块也提供api/rpc 实现[moduleId]/api/rpc
 * ...............................
 * 还有一种实现通过Yii::app()->runController  实现内部调用 用jsonRpc实现外部调用！
 * the format
 * BUNDLE_NAME:CONTROLLER_NAME:ACTION_NAME. For instance, AcmeDemoBundle:Welcome:index maps to
 *  the indexAction method from the Acme\DemoBundle\Controller\WelcomeController class
 * 对外是逻辑名称 对内需要映射到物理名称上
 * ................................
 * YsService::instance()->call('<moduleId>','serviceName:methodName',array(....))
 *
 * ---------------------------------------------------------------------
 * 2014-2-10
 * 由于控制器类名原因 利用runController 方法在无名空间支持时容易引起名称冲突！
 * 现在直接将service作为模型方法暴露
 *
 */
class YsModuleService extends CApplicationComponent
{

    /**
     * @static
     * @return YsModuleService
     */
    public static function instance()
    {
        if (!isset(Yii::app()->moduleService)) {

            Yii::app()->setComponents(array(
                'moduleService' => array(
                    'class' => __CLASS__,
                   // 'mode' => self::MODE_LOCAL,
                ),
            ), false);
        }
        return Yii::app()->moduleService;
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


    /**
     * @param string $moduleId
     * @param string $serviceName
     * @param array $params
     * @return mixed
     * @throws CException
     */
    public function callModuleService($moduleId = '', $serviceName = '', $params = array())
    {

        if ($this->mode = self::MODE_LOCAL) {
            return $this->callLocalModuleService($moduleId, $serviceName, $params);
        }  else {
            throw new CException("the {$this->model} is not supported yet");
        }
    }

    /**
     * @param string $moduleId
     * @param string $serviceName
     * @param array $params
     * @return mixed
     *
     * 服务作为门面方法暴露 参考dolphin
     *
     */
    protected function callLocalModuleService($moduleId = '', $serviceName = '', $params = array())
    {
        $module = Yii::app()->getModule($moduleId);
        $serviceName = 'service'.ucfirst($serviceName);
        return call_user_func_array(array($module,$serviceName),array($params));
    }


    /**
     * @param string $moduleId
     * @param string $serviceName
     * @param array $params
     * @return mixed
     *  YsModuleService::instance()->call('<moduleId>','serviceName:methodName',array(....))
     * 第二个参数需要各个模块自己解析 或者用惯例
     */
    protected function callLocalModuleService0($moduleId = '', $serviceName = '', $params = array())
    {
        $route = '';
        if(empty($moduleId)){
            $route = "api/moduleService";
        }else{
            $route = "{$moduleId}/api/moduleService";
        }
        $old = array();
        if(isset($_POST['service'])){
            $old['service'] = $_POST['service'];
        }
        if(isset($_POST['params'])){
            $ol['params'] = $_POST['params'];
        }
        $_POST['service'] = $serviceName ;
        $_POST['params'] = $params ;
        echo $route ;
        // 使用post传值
        Yii::app()->runController($route);

        // 恢复post中的原始值
        unset($_POST['service'],$_POST['params']);
        $_POST = array_merge($_POST,$old);

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
