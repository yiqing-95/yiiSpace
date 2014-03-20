<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:19
 * To change this template use File | Settings | File Templates.
 */
class GroupInstaller extends BaseModuleInstaller
{

    public function install(){
        echo __METHOD__ ;
        // 添加必要菜单给系统总线
        // 先使用 简单粗暴的方法试试
        //   CHtml::link('label','url',array());
        $topNavArr['Group'] = array(
            'text'=>'小组',
            'url'=>array('/group/group/listMemberGroups'),
        );
        YsNavSystem::addUserSpaceNav('top_nav','Group',$topNavArr);
        // 安装 用户中心的应用菜单
        YsNavSystem::addUserCenterNav('side_nav','Group',array(
            'main'=>array(
                'text'=>'我的小组',
                'url'=>array(
                    '/group/group/listMyGroups',
                )
            ),
            'topic'=>array(
                'text'=>'我的话题',
                'url'=>array(
                    '/group/groupTopic/listMyTopics',
                )
            ),
            'post'=>array(
                'text'=>'我的帖子',
                'url'=>array(
                    '/group/groupTopicPost/listMyPosts',
                )
            )
        ));

        $mig = new GroupInstallMigration();
        $mig->up();

    }

    public function uninstall(){
        echo __METHOD__ ;
        // 卸载系统菜单
        YsNavSystem::removeUserSpaceNav('top_nav','Group');
        YsNavSystem::removeUserCenterNav('side_nav','Group');

        $mig = new GroupInstallMigration();
        $mig->down();
    }


}


class GroupInstallMigration extends CDbMigration
{


}
