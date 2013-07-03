<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-9-27
 * Time: 下午1:33
 * To change this template use File | Settings | File Templates.
 */
class YsServiceTest extends CDbTestCase
{

    public function testServiceCall(){
       $serviceProxy  = YsService::instance();
       $response = $serviceProxy->callModuleService('test','sayHi');
        $this->assertEquals('hi',$response);
    }
}
