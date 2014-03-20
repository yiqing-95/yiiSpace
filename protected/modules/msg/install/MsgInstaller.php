<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:19
 * To change this template use File | Settings | File Templates.
 */
class MsgInstaller extends BaseModuleInstaller
{

    public function install(){
        echo __METHOD__ ;

        // 此配置是历程碑式的出现
        $topNavArr['Msg'] = array(
           'class'=>'msg.widgets.SndMsgWidget',
        );
        YsNavSystem::addUserSpaceNav('profile_nav','Msg',$topNavArr);

        // 安装 用户中心的应用菜单
        YsNavSystem::addUserCenterNav('side_nav','Msg',array(
            'main'=>array(
                'text'=>'我的消息',
                'url'=>array(
                    '/msg/msg/inbox',
                )
            ),

        ));

        $mig = new MsgInstallMigration();
        $mig->up();

    }

    public function uninstall(){
        echo __METHOD__ ;
        // 卸载系统菜单
        YsNavSystem::removeUserSpaceNav('profile_nav','Msg');
        YsNavSystem::removeUserCenterNav('side_nav','Msg');

        $mig = new MsgInstallMigration();
        $mig->down();
    }


}


class MsgInstallMigration extends CDbMigration
{


}
