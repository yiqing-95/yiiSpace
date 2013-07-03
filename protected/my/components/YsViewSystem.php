<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

define ('YS_OLD_VIEWS', 3*86400); // views older than this number of seconds will be deleted automatically
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-30
 * Time: 下午4:59
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------------
 * 通用的点击量处理
 * ----------------------------------------------------------------
 * 做这种系统穿越性功能 要考虑组件中的方法上下文发生在哪里？
 * 是在系统还是转移到了 下面的module中去了 如果是在系统（功能执行发生于
 * 非任何module环境）那么要考虑是否记录事件的发生点 这样可能要求方法多出参数
 * 来传递currentModuleId 这样的东东！
 * ----------------------------------------------------------------
 * 穿越多个module的功能基本出发点事基于model 围绕AR 做文章的
 * 然后还会多出一个配置功能如何实现的需求，比如新安装了一个module
 * 里面会有某些model需要这种系统功能 那么它要挂接自己的配置到系统中
 * 所以需要某种钩子接口，便于各个module注册自己的AR附加功能配置
 * 通过main配置文件 理论上也是可以的 但不灵活比较死套
 * ----------------------------------------------------------------
 */
class YsViewSystem
{

    public static function doView($sysName='',$id=0){
        $systems = self::getAllSystems ();
        if (!isset($systems[$sysName])){
            return;
        }
         $sysObjView = SysObjectView::model()->populateRecord($systems[$sysName]);
        $sysObjView->doView($id);
        }

    public static  function getAllSystems ()
    {
        $cacheKey = __METHOD__;
        $systems = Yii::app()->cache->get($cacheKey);
        if($systems === false)
        {
            $db = Yii::app()->db;
            $cmd = $db->createCommand( 'SELECT * FROM `sys_object_view`');
            $dataReader = $cmd->query();
          // calling read() repeatedly until it returns false
          //  while(($row = $dataReader->read())!==false) { ... }
// using foreach to traverse through every row of data
            $systems = array();
            foreach($dataReader as $row) {
                $systems[$row['name']] = $row;
            }
            $dependency = new CFileCacheDependency(/*Yii::getPathOfAlias()*/Yii::app()->getModulePath());
            // regenerate $value because it is not found in cache
            // and save it in cache for later use:
             Yii::app()->cache->set($cacheKey,$systems,86400,$dependency);

        }
        return $systems ;

    }




    /**
     * @return int
     * // it is called on cron every day or similar period
     */
    public  function maintenance ()
    {
        $time = time() - YS_OLD_VIEWS;
        $systems = self::getAllSystems();
        $iDeletedRecords = 0;
        $db = Yii::app()->db;
        $cmd = $db->createCommand();
        foreach ($systems as $system) {
            if (!$system['enable']){
                continue;
            }

            $iDeletedRecords += $cmd->setText("DELETE FROM `{$system['table_track']}` WHERE `ts` < {$time}")->execute();
            $cmd->setText("OPTIMIZE TABLE `{$system['table_track']}`")->execute();
        }
        return $iDeletedRecords;
    }


    /**
     * @static
     * @param $arClassName
     * @param $tableTrack
     * @param $triggerTable
     * @param int $period
     * @param string $triggerFieldId
     * @param string $triggerFieldViews
     * @return bool
     * 好像没有记录来自哪个module
     * 不过第一个参数 如果不用ar的类名的话可以考虑
     * 带点那种格式:  photo.album
     */
    public static function registerObjectViewConfig($arClassName,$tableTrack,$triggerTable,$period=86400,$triggerFieldId='id',$triggerFieldViews='views'){
         $sysObj = new SysObjectView();
        $sysObj->name = $arClassName;
        $sysObj->table_track = $tableTrack;
        $sysObj->period = $period;
        $sysObj->trigger_table = $triggerTable;
        $sysObj->trigger_field_id = $triggerFieldId;
        $sysObj->trigger_field_views = $triggerFieldViews;
        $sysObj->enable = 1 ;
        return $sysObj->save();

    }

    /**
     * @static
     * @param $arClassName
     * @return int
     */
    public static function unRegisterObjectViewConfig($arClassName){
       return SysObjectView::model()->deleteAllByAttributes(array(
          'name'=>$arClassName,
       ));
    }
}


