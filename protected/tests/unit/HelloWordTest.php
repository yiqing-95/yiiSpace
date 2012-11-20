<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-9-27
 * Time: 下午1:33
 * To change this template use File | Settings | File Templates.
 */
class HelloWordTest extends CDbTestCase
{

    public function testHelloWord(){
        $str = 'hello2';
        $this->assertNotEquals('hello',$str);
    }
}
