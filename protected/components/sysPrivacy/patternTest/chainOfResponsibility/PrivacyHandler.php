<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-25
 * Time: 上午10:02
 * To change this template use File | Settings | File Templates.
 */
abstract class PrivacyHandler
{
    //--------------------------------------
    /**
     * added in  version 1.1
     */
    /**
     * @var bool whether the task is handled
     * if true the handle will be stopped
     * @since version 1.1 ;
     */
    protected  $isHandled = false ;

    public function getIsHandled(){
        return $this->isHandled;
    }
    //--------------------------------------

    /**
     * @var PrivacyHandler
     */
    protected $successor ;

    /**
     * @param PrivacyHandler $successor
     */
    public function setSuccessor($successor ){
        $this->successor = $successor;
    }

    public abstract function  handle();
}
