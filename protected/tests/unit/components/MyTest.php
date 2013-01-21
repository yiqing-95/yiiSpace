<?php
/**
 * User: yiqing
 * Date: 13-1-13
 * Time: 上午1:54
 * change template => | Settings | File Templates.
 * ------------------------------------------------
 * ------------------------------------------------
 */

class MyTest extends CDbTestCase
{
    public function testGetHomeUrl(){
        $rtn = My::getHomeUrl();
        $this->assertContains('localhost',$rtn);

    }
}
