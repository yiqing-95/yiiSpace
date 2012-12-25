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
