<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午3:42
 * To change this template use File | Settings | File Templates.
 */
class YsSelfChecker extends PrivacyGroupChecker
{

    /**
     * This method should be overridden by child classes.
     */
    public  function check()
    {
       return $this->objectOwner == $this->viewer;
    }
}
