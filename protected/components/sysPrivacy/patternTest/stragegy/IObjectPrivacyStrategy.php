<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-20
 * Time: 下午2:53
 * To change this template use File | Settings | File Templates.
 */
interface IObjectPrivacyStrategy
{

    /**
     * @abstract
     * @return bool
     */
    public function isAllowed();
}
