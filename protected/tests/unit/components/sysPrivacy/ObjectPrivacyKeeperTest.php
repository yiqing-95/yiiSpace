<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-23
 * Time: 上午12:04
 * To change this template use File | Settings | File Templates.
 */
Yii::import('application.components.sysPrivacy.*');
class ObjectPrivacyKeeperTest extends CTestCase
{

    public function testIsPublic(){
        $privacyKeeper = new ObjectPrivacyKeeper();
        $this->assertTrue($privacyKeeper->check(1,1,1)==true);
    }

    public function testIsFriend(){
        $privacyKeeper = new ObjectPrivacyKeeper();
        $this->assertTrue($privacyKeeper->check(2,1,2)==true);
    }

    public function testIsSelf(){
        $privacyKeeper = new ObjectPrivacyKeeper();
        $this->assertTrue($privacyKeeper->check(3,1,1)==true);
    }


    public function testIsNotFriend(){
        $privacyKeeper = new ObjectPrivacyKeeper();
        $this->assertTrue($privacyKeeper->check(2,1,-2)===false);
    }

}
