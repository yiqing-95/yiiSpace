<?php
/**
 *
 * User: yiqing
 * Date: 13-3-24
 * Time: 下午6:17
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */
interface IUser
{
    /**
     * @param $username
     * @param $password
     * @return array
     * {error:0|1,}
     */
    public function register($username,$password);
}
