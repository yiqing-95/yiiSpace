<?php

Yii::import('application.models._base.BaseSysModule');

class SysModule extends BaseSysModule
{
public static function model($className=__CLASS__) {
return parent::model($className);
}

    /**
     * @static
     * @return array
     */
    public static function getInstalledModuleIds(){
        $installedModules = array();
        foreach(SysModule::model()->findAll() as $module){
            $installedModules[] =   $module->module_id;
        }
        return $installedModules;
    }
}