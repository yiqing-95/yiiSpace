<?php
/**
 * User: yiqing
 * Date: 13-1-21
 * Time: 上午10:23
 * change template => | Settings | File Templates.
 * ------------------------------------------------
 * ------------------------------------------------
 */
interface ITestService
{
    /**
     * @abstract
     * @param string $param
     * @return string
     */
    public function helloTo($param='');
}
