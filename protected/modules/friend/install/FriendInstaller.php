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
        // 添加必要菜单给系统总线
        // 先使用 简单粗暴的方法试试
        //   CHtml::link('label','url',array());
        $topNavArr['friend'] = array(
            'text'=>'好友',
            'url'=>array('/friend/relationship/viewAll'),
        );
        YsNavSystem::addUserSpaceNav('top_nav','friend',$topNavArr);
        // 安装 用户中心的应用菜单
        YsNavSystem::addUserCenterNav('side_nav','friend',array(
            'main'=>array(
                'text'=>'好友',
                'url'=>array(
                    '/friend/relationship/myRelationships',
                )
            )
        ));

        $mig = new FriendInstallMigration();
        $mig->up();

    }

    public function uninstall(){
        echo __METHOD__ ;
        ActionFeedManager::unRegisterFeedHandler('Relationship');
        // 卸载系统菜单
        YsNavSystem::removeUserSpaceNav('top_nav','friend');
        YsNavSystem::removeUserCenterNav('side_nav','friend');

        $mig = new FriendInstallMigration();
        $mig->down();
    }


}


class FriendInstallMigration extends CDbMigration
{

    public function safeUp()
    {


        //  注册状态墙动态处理器
        $this->insert('status_type', array(
            'type_name' => 'follow a member ',
            'id' => 'user_following',
            'active' => 1,
            'handler' => 'friend.statusWall.FriendStatusHandler',
            'is_core' => 0,
        ));

    }

    public function safeDown()
    {
        $this->delete('status_type', 'id=:type_ref', array(':type_ref' => 'user_following'));
    }
}
