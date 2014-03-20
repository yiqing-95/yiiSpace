<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-25
 * Time: 上午12:32
 * To change this template use File | Settings | File Templates.
 */
class ApiController extends CController
{

    /**
     * 参考YsModuleService类
     * @return mixed
     */
    public function actionModuleService(){
      echo CJSON::encode( $_POST );
    }

    public function actionRpc()
    {
        //  die(__METHOD__);
        Yii::log("enter " . __METHOD__, CLogger::LEVEL_INFO);
        Yii::import('application.vendors.json_rpc.jsonRPCServer');

        $serviceProxy = YsService::instance();

        jsonRPCServer::handle($serviceProxy)
            or print 'no request';
        Yii::log("end " . __METHOD__, CLogger::LEVEL_INFO);
        die();
    }


    /**
     * 用 apache tcpMon 来测试soap rpc 等
     * @see http://ws.apache.org/commons/tcpmon/download.cgi
     */
    public function actionJsonRpc()
    {
        $request = Yii::app()->request;

        $moduleId = $request->getParam('module');
        $method = $request->getParam('method');
        // 下面将根据不同的moduleId 和 method 来绑定不同的服务类实例
        // Yii::log("enter ". __METHOD__, CLogger::LEVEL_INFO);
        Yii::import('application.vendors.json_rpc.jsonRPCServer');

        $serviceFacadeObj = $this->findServiceFacade($moduleId);
        if (!is_null($serviceFacadeObj)) {
            $serviceObj = call_user_func_array(array($serviceFacadeObj, $method), array());
            jsonRPCServer::handle($serviceObj)
                or print 'no request';
            //  Yii::log("end ". __METHOD__, CLogger::LEVEL_INFO);
            //die();
            Yii::app()->end(0);
        } else {
            // 不存在 服务对象
            die("bad request " . print_r($_REQUEST));
        }
        exit();

    }


    /**
     * Finds services facade of the specified moduleId
     * if moduleId is null or "app" then will search from the "application" alias path .
     * @param string $moduleId
     * @return object
     * -----------------------------
     * TODO : 后期用缓存来加速下
     * -----------------------------
     */
    protected function findServiceFacade($moduleId = '')
    {
        $serviceFacadeObj = null;

        if (empty($moduleId) || $moduleId == 'app') {
            $modulePath = Yii::app()->getBasePath();
        } else {
            $modulePath = Yii::app()->getModule($moduleId)->basePath;
        }
        $servicePath = $modulePath . '/services';
        if (is_dir($servicePath)) {
            $serviceFacadeClass = ucfirst($moduleId) . 'ServiceHolder';
            if (is_file("$servicePath/$serviceFacadeClass.php")) {
                if (!class_exists($serviceFacadeClass)) {
                    require("$servicePath/$serviceFacadeClass.php");
                    $serviceFacadeObj = call_user_func_array(array($serviceFacadeClass, 'instance'), array());
                } else {
                    $serviceFacadeObj = call_user_func_array(array($serviceFacadeClass, 'instance'), array());
                }

            } else {
                // 非标准命名时需要遍历每个php文件 然后看其是否实现了某接口！
                $names = scandir($servicePath);
                foreach ($names as $name) {
                    if (is_dir($servicePath . '/' . $name)) {
                        continue;
                    }
                    $className = pathinfo($name, PATHINFO_FILENAME);
                    if (!class_exists($className, false)) {
                        require("$servicePath/$className.php");
                        if (class_exists($className, false) && is_subclass_of($className, 'IModuleServiceFacade')) {
                            $serviceFacadeObj = call_user_func_array(array($serviceFacadeClass, 'instance'), array());
                            break;
                        }
                    } else {
                        if (is_subclass_of($className, 'IModuleServiceFacade')) {
                            $serviceFacadeObj = call_user_func_array(array($serviceFacadeClass, 'instance'), array());
                            break;
                        }
                    }
                }
            }
        }

        return $serviceFacadeObj;
    }

}
