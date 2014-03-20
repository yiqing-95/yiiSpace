<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 13-2-3
 * Time: 下午11:06
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------------
 * from : 吴涛 爱天意网站的作者 特此感谢！
 * -------------------------------------------------------------
 */
class YsControllerBehavior extends CBehavior
{


    /**
    +----------------------------------------------------------
     * 操作错误跳转的快捷方法
    +----------------------------------------------------------
     * @access protected
    +----------------------------------------------------------
     * @param string $message 错误信息
     * @param string $returnUrl 跳转地址
     * @param null $data
     * @internal param bool $ajax 是否为Ajax方式
    +----------------------------------------------------------
     * @return void
    +----------------------------------------------------------
     */
    public function error($message, $returnUrl = null, $data = null) {
        $this->ajaxReturn($data, $message, 0, $returnUrl);

    }

    /**
    +----------------------------------------------------------
     * 操作提醒跳转的快捷方法
    +----------------------------------------------------------
     * @access protected
    +----------------------------------------------------------
     * @param string $message 提醒星系
     * @param string $returnUrl 跳转地址
     * @param null $data
     * @internal param bool $ajax 是否为Ajax方式
    +----------------------------------------------------------
     * @return void
    +----------------------------------------------------------
     */
    public function warn($message, $returnUrl = null, $data = null) {
        $this->ajaxReturn($data, $message, 2, $returnUrl);

    }

    /**
    +----------------------------------------------------------
     * 操作成功跳转的快捷方法
    +----------------------------------------------------------
     * @access protected
    +----------------------------------------------------------
     * @param string $message 提示信息
     * @param string $returnUrl 跳转地址
     * @param null $data
     * @internal param bool $ajax 是否为Ajax方式
    +----------------------------------------------------------
     * @return void
    +----------------------------------------------------------
     */
    public function success($message, $returnUrl = null,$data = null) {

        $this->ajaxReturn($data, $message, 1, $returnUrl);
    }

    /**
    +----------------------------------------------------------
     * Ajax方式返回数据到客户端
    +----------------------------------------------------------
     * @access protected
    +----------------------------------------------------------
     * @param mixed $data 要返回的数据
     * @param String $info 提示信息
     * @param bool|int $status ajax返回类型 JSON XML

    +----------------------------------------------------------
     * @param string $returnUrl 跳转地址
     * @param string $type
     * @return void
    +----------------------------------------------------------
     */
    public function ajaxReturn($data, $info = '', $status = 1, $returnUrl=null,$type = 'JSON') {
        $result = array();
        $result['status'] = $status;
        $result['info'] = $info;
        $result['data'] = $data;
        if($returnUrl)
            $result['returnUrl'] = $returnUrl;
        if (strtoupper($type) == 'JSON') {
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:text/html; charset=utf-8');
            exit(json_encode($result));
        } elseif (strtoupper($type) == 'XML') {
            // 返回xml格式数据
            header('Content-Type:text/xml; charset=utf-8');
            exit(xml_encode($result));
        } elseif (strtoupper($type) == 'EVAL') {
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            exit($data);
        } else {
            // TODO 增加其它格式
        }
    }

}
