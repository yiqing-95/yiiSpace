<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-24
 * Time: 下午11:48
 * To change this template use File | Settings | File Templates.
 * ------------------------------------------------------------------
 * 实现模块间通讯
 * ------------------------------------------------------------------
 */
interface IModuleService
{

    /**
     * @abstract
     * @param $mode
     * @return mixed
     * 设置服务的模式：local | rpc(xml,json.php)
     * 服务实现时可据此参数 返回不同格式的方法结果
     */
    public function setProcessMode($mode);
}
