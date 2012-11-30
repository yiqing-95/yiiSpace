<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-9-30
 * Time: 上午6:55
 * To change this template use File | Settings | File Templates.
 */
interface ISysEventListener
{

  public function handleEvent(GEvent $e);
 }
