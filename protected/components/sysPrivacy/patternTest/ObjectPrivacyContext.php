<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午5:37
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------
 * 封装策略需要的参数  如果需要修改 也只局限于这里 可以用继承！！
 * 不会感染到策略对象的构造方法
 * 原来构造方法接受两个参数 现在固定为一个！！
 * ----------------------------------------------
 * 参考《研磨 设计模式》(陈臣 王斌 著)
 * ----------------------------------------------
 * 策略选择可以在上下文做 也可以在client 里面做！
 * 容错恢复机制的实现：
 *  try{
 *        $strategy1.doSomething();
 *   } cache(Exception $e){
 *        $strategy2.doSomething();
 *  }
 * 思想：  策略1失效或异常后自动切换到另一种策略上！完全自动化不用人为干预.
 */
class ObjectPrivacyContext
{
    /**
     * @var int
     */
    protected  $objectOwner = 1;
    /**
     * @var int
     */
    protected $viewer = 1;

    /**
     * @var IObjectPrivacyStrategy ;
     */
    protected $privacyStrategy ;

    /**
     * @return int
     */
    public function getObjectOwner(){
        return $this->objectOwner;
    }

    /**
     * @param $objectOwner
     */
    public function setObjectOwner($objectOwner){
        $this->objectOwner = $objectOwner;
    }

    /**
     * @return int
     */
    public function getViewer(){
        return $this->viewer;
    }

    /**
     * @param int $viewer
     */
    public function setViewer($viewer =1){
        $this->viewer = $viewer ;
    }

    /**
     * @return bool
     */
    public function isAllowed(){
        return $this->privacyStrategy->isAllowed();
    }
}
