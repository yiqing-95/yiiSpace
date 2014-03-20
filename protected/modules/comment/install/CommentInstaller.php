<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:19
 * To change this template use File | Settings | File Templates.
 */
class CommentInstaller extends BaseModuleInstaller
{

    public function install(){
        echo __METHOD__ ;
        // 添加必要菜单给系统总线
        // 先使用 简单粗暴的方法试试
        //   CHtml::link('label','url',array());
       /*
        $topNavArr['Comment'] = array(
            'text'=>'小组',
            'url'=>array('/group/group/listMemberComments'),
        );
        YsNavSystem::addUserSpaceNav('top_nav','Comment',$topNavArr);
       */
        // 安装 用户中心的应用菜单
        YsNavSystem::addUserCenterNav('side_nav','Comment',array(
            'my'=>array(
                'text'=>'发出的评论',
                'url'=>array(
                    '/comment/comment/sent',
                )
            ),
            'toMe'=>array(
                'text'=>'收到的评论',
                'url'=>array(
                    '/comment/comment/received',
                )
            ),
        ));

        $mig = new CommentInstallMigration();
        $mig->up();

    }

    public function uninstall(){
        echo __METHOD__ ;
        // 卸载系统菜单
       // YsNavSystem::removeUserSpaceNav('top_nav','Comment');
        YsNavSystem::removeUserCenterNav('side_nav','Comment');

        $mig = new CommentInstallMigration();
        $mig->down();
    }


}


class CommentInstallMigration extends CDbMigration
{


}
