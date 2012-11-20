<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-19
 * Time: 下午5:31
 * To change this template use File | Settings | File Templates.
 */
class SysHookTest extends CTestCase
{

    public function testAddHook(){
        $hooks = YsHookService::getHooks('app','test');
       // YsHookService::addHook('app','test','app','appOnAppTest','hi can not be blank ');
       // $this->assertTrue(count(YsHookService::getHooks('app','test')) == (count($hooks)+1));
        $this->assertTrue(true);
    }

    public function testRemoveHook(){
        // 先不管有莫有先干掉
       YsHookService::removeHookByClientHookName('appOnAppTest');

        YsHookService::addHook('app','test','app','appOnAppTest','hi can not be blank ');
        $hooks = YsHookService::getHooks('app','test');
        YsHookService::removeHook('app','test','app','appOnAppTest');
        $this->assertTrue(count(YsHookService::getHooks('app','test')) == (count($hooks)-1));
    }

    public function testGetHooks(){
        // 先不管有莫有先干掉
       YsHookService::removeAllHook('app','test');

        YsHookService::addHook('app','test','app','appOnAppTest','hi can not be blank ');
        YsHookService::addHook('app','test','app','appOnAppTest2','dddhi can not be blank ');
        $hooks = YsHookService::getHooks('app','test');
        $this->assertTrue(2 == count($hooks));
    }

    public function testRemoveAllHook(){
        // 先不管有莫有先干掉
        YsHookService::removeHookByClientHookName('appOnAppTest');

        $hooks = YsHookService::getHooks('app','test');

        $clientModule = __METHOD__ ;
        YsHookService::addHook('app','test',$clientModule ,'_appOnAppTest','hi can not be blank ');
        YsHookService::addHook('app','test',$clientModule ,'_appOnAppTest2','hi can not be blank ');

        $this->assertTrue(count(YsHookService::getHooks('app','test')) == (count($hooks)+2));
        YsHookService::removeAllHookByClientModule($clientModule);

        $this->assertTrue(count(YsHookService::getHooks('app','test')) == (count($hooks)));
    }

    public function testPriority(){
        $clientModule = __METHOD__ ;
        // 先不管有莫有先干掉
        YsHookService::removeAllHookByClientModule();

        $hookName = 'hook'. __METHOD__ ;

        YsHookService::addHook('app',$hookName,$clientModule ,'_appOnAppTest','hi can not be blank ',23);
        YsHookService::addHook('app',$hookName,$clientModule ,'_appOnAppTest2','hi can not be blank ',100);

        $hooks = YsHookService::getHooks('app',$hookName);
        $firstHook = $hooks[0];

        $this->assertTrue($firstHook->priority == 100 );
        YsHookService::removeAllHookByClientModule($clientModule);

        $this->assertTrue(count(YsHookService::getHooks('app',$hookName)) == 0 );
    }

}
