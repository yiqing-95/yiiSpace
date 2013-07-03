<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-23
 * Time: 上午12:04
 * To change this template use File | Settings | File Templates.
 */
Yii::import('application.components.sysPrivacy.patternTest.chainOfResponsibility.*');
class PrivacyManTest extends CTestCase
{

    public function testDummy(){
        $privacyMan = new PrivacyMan();
        $privacyMan->check(1,1,1);
    }

}
