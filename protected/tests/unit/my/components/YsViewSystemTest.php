<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-1
 * Time: 上午12:40
 * To change this template use File | Settings | File Templates.
 */
class YsViewSystemTest extends CDbTestCase
{

    public function testHelloWord(){
        $str = 'hello2';
        $this->assertNotEquals('hello',$str);
    }

}
