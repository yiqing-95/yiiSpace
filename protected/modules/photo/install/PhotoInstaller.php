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

       YsHookService::addHook('app','createUrl','photo','photo_appCreateUrl',CJSON::encode(array(
           'route'=>array('album/member','/album/member'),
           'paramsExpression'=>'isset($_GET[\'u\'])?$params+array(\'u\'=>$_GET[\'u\']):$params;'
       )));

        YsViewSystem::registerObjectViewConfig('photo','photo_view_track','photo');

        YsVotingSystem::registerSysObjectVoteConfig('photo','photo_rating','photo_vote_track','pt_',5,0,'photo','rate','rate_count','id');
        echo __METHOD__ ;

    }

    public function uninstall(){
        YsHookService::removeAllHookByClientModule('photo');

        YsViewSystem::unRegisterObjectViewConfig('photo');
        YsVotingSystem::unRegisterObjectVotingConfig('photo');
        echo __METHOD__ ;
    }


}
