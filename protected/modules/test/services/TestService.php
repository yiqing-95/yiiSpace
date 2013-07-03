<?php
$alias = md5(__FILE__);
Yii::setPathOfAlias($alias,dirname(__FILE__));
Yii::import($alias.'.interfaces.ITestService');
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-25
 * Time: 上午12:01
 * To change this template use File | Settings | File Templates.
 */
class TestService implements IModuleService
{

    protected  $processMode = '';

    public function getServiceMode($param=''){
        return $this->processMode .$param;
    }
    public function sayHi(){
        return 'hi' ;
    }

    /**
     * @param $mode
     * @return mixed
     * 设置服务的模式：local | rpc(xml,json.php)
     * 服务实现时可据此参数 返回不同格式的方法结果
     */
    public function setProcessMode($mode)
    {
        $this->processMode = $mode ;
    }
}
