<?php
/**
 *  
 * User: yiqing
 * Date: 13-5-17
 * Time: 上午10:48
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

abstract class AbstractStatusHandler  {

    /**
     * @var string
     */
    public $actorLink ;

    /**
     * @var User|array
     */
    public $actor ;

    /**
     * @var array
     */
    public $data ;

    /**
     * 这个方法可以用来做data的反序列化
     * 便于renderTitle和renderBody不必多次
     * 反序列data数据
     */
    public function init(){

    }

    /**
     * @return mixed
     */
    public function renderTitle(){

    }

    /**
     * @return mixed
     */
    public function renderBody(){

    }
}