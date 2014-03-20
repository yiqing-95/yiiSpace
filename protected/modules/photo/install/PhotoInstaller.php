<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-24
 * Time: 下午12:19
 * To change this template use File | Settings | File Templates.
 */
class PhotoInstaller extends BaseModuleInstaller
{

    public function install(){

        //$this->uninstall() ;

       YsHookService::addHook('app','createUrl','photo','photo_appCreateUrl',CJSON::encode(array(
           'route'=>array('album/member','/album/member'),
           'paramsExpression'=>'isset($_GET[\'u\'])?$params+array(\'u\'=>$_GET[\'u\']):$params;'
       )));

        YsViewSystem::registerObjectViewConfig('photo','photo_view_track','photo');

        YsVotingSystem::registerSysObjectVoteConfig('photo','photo_rating','photo_vote_track','pt_',5,0,'photo','rate','rate_count','id');

        /**
         *  array(
        'object_name' => $object_name, //string
        'table_cmt' => $table_cmt, //string
        'table_track' => $table_track, //string
        'per_view' => $per_view, //integer
        'is_ratable' => $is_ratable, //integer
        'is_on' => $is_on, //integer
        'is_mood' => $is_mood, //integer
        'trigger_table' => $trigger_table, //string
        'trigger_field_id' => $trigger_field_id, //string
        'trigger_field_cmts' => $trigger_field_cmts, //string
        'class' => $class, //string
        'extra_config' => $extra_config,
         */
        YsCommentSystem::registerSysObjectCmtConfig(
            array('object_name'=>'photo',
           'table_cmt'=>'photo_cmt',
            'trigger_table'=>'photo',
            'trigger_field_id'=>'id',
            'trigger_field_cmts'=>'cmt_count'
        ));

        YsThumbVotingSystem::addUpDownCounterColumns('photo');
        YsThumbVotingSystem::registerConfig('photo','photo_thumb_vote','photo');

        // 添加必要菜单给系统总线
        // 先使用 简单粗暴的方法试试
       //   CHtml::link('label','url',array());
        $topNavArr['photo'] = array(
          'text'=>'相册',
          'url'=>array('/album/member'),
        );
        YsNavSystem::addUserSpaceNav('top_nav','photo',$topNavArr);
        // 安装 用户中心的应用菜单
        YsNavSystem::addUserCenterNav('side_nav','photo',array(
           'main'=>array(
               'text'=>'相册',
               'url'=>array(
                   '/album/my',
               )
           )
        ));

        // 安装照片收藏功能的顶部菜单
        YsNavSystem::addUserCenterNav('user_glean_nav','photo',array(
            'main'=>array(
                'text'=>'收藏的照片',
                'url'=>array(
                    '/photo/glean/list',
                ),
                'htmlOptions'=>array(

                ),
                // 这个是给菜单容器用的
                'htmlOptionsExpression'=>' array("class"=>(controller()->getModule()->getId() == "photo" )? "active":"" ) ',
            )
        ));



        echo __METHOD__ ;
    }

    public function uninstall(){
        YsHookService::removeAllHookByClientModule('photo');

        YsViewSystem::unRegisterObjectViewConfig('photo');
        YsVotingSystem::unRegisterObjectVotingConfig('photo');

        YsCommentSystem::unRegisterObjectCommentConfig('photo');

        YsThumbVotingSystem::dropUpDownCounterColumns('photo');
        YsThumbVotingSystem::unRegisterConfig('photo');


        // 卸载系统菜单
        YsNavSystem::removeUserSpaceNav('top_nav','photo');
        YsNavSystem::removeUserCenterNav('side_nav','photo');
        echo __METHOD__ ;
    }


}
