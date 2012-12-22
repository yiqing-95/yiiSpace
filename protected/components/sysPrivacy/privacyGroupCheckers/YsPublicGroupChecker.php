<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午2:58
 * To change this template use File | Settings | File Templates.
 */
class YsPublicGroupChecker extends PrivacyGroupChecker
{
    /**
     * This method should be overridden by child classes.
     */
    public  function check()
    {
         return true;
    }
}
