<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:19
 * To change this template use File | Settings | File Templates.
 */
class FriendInstaller extends BaseModuleInstaller
{

    public function install(){
       // echo __METHOD__ ;
        ActionFeedManager::registerFeedHandler('Relationship','application.modules.friend.components.RelationshipFeedHandler');

    }

    public function uninstall(){
        echo __METHOD__ ;
        ActionFeedManager::unRegisterFeedHandler('Relationship');
    }


}
