<?php
Yii::app()->getModule('blog');
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:19
 * To change this template use File | Settings | File Templates.
 */
class BlogInstaller extends BaseModuleInstaller
{

    public function install()
    {

        echo __METHOD__;
        $mig = new BlogInstallMigration();
        $mig->up();


        $topNavArr['blog'] = array(
            'text'=>'日志',
            'url'=>array('/blog/member/list'),
        );
        YsNavSystem::addUserSpaceNav('top_nav','blog',$topNavArr);
        // 安装 用户中心的应用菜单
        YsNavSystem::addUserCenterNav('side_nav','blog',array(
            'main'=>array(
                'text'=>'日志',
                'url'=>array(
                    '/blog/my',
                )
            )
        ));
    }

    public function uninstall()
    {
        echo __METHOD__;
        $mig = new BlogInstallMigration();
        $mig->down();


        // 卸载系统菜单
        YsNavSystem::removeUserSpaceNav('top_nav','blog');
        YsNavSystem::removeUserCenterNav('side_nav','blog');

        // 安装照片收藏功能的顶部菜单
        YsNavSystem::addUserCenterNav('user_glean_nav','blog',array(
            'main'=>array(
                'text'=>'收藏的日志',
                'url'=>array(
                    '/blog/glean/list',
                ),
                'htmlOptions'=>array(

                ),
                // 这个是给菜单容器用的
                'htmlOptionsExpression'=>' array("class"=>(controller()->getModule()->getId() == "blog" )? "active":"" ) ',
            )
        ));
    }


}

class BlogInstallMigration extends CDbMigration
{

    public function safeUp()
    {

        /**
         * VALUES ('id',
        'type_name',
        'type_reference',
        'active',
        'handler',
        'is_core');
         */

        //  注册动态处理器
        $this->insert('status_type', array(
            'type_name' => 'post a blog ',
            'id' => 'blog_create',
            'active' => 1,
            'handler' => 'blog.statusWall.BlogStatusHandler',
            'is_core' => 0,
        ));


        AdminMenu::addTempAdminMenu(array(
           'label'=>'日志系统分类管理',
            'url'=>'/blog/blogSysCategory/admin',
        ));

        AdminMenu::addTempAdminMenu(array(
            'label'=>'日志系统分类创建',
            'url'=>'/blog/blogSysCategory/create',
        ));
    }

    public function safeDown()
    {
        $this->delete('status_type', 'id=:type_ref', array(':type_ref' => 'blog_create'));
    }
}

