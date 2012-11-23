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
           'paramsExpression'=>'$params+array(\'u\'=>$_GET[\'u\'])'
       )));

        echo __METHOD__ ;

    }

    public function uninstall(){
        YsHookService::removeAllHookByClientModule('photo');
        echo __METHOD__ ;
    }


}
