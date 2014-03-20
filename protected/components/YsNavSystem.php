<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-12-26
 * Time: 上午9:59
 * To change this template use File | Settings | File Templates.
 */
/**
 * 维护管理系统的菜单导航系统
 * Class YsNavSystem
 */
class YsNavSystem {
    /**
     * 缓存键前缀 防止跟系统的其他键冲突！ 名空间做前缀是个好习惯哦！
     * @var string
     */
    public static  $settingKeyPrefix = 'ys_nav';
    //=============================================================\\
    //=====
    /**
     * 本段是通用实现 劲量不要直接调用哈！@
     */
    //=====
    /**
     * @param string $pageId
     * @param string $position
     * @param string $fromModuleId
     * @param array $menuConfig
     * @return bool
     */
    public static  function addNav($pageId ,$position, $fromModuleId,$menuConfig){

        $settings = AppComponent::settings();
        $cateKey =  self::$settingKeyPrefix .':'. $pageId .':' .$position ;
        $settingsKey = $fromModuleId ;

        $topNavArr  = $settings->get($cateKey,$settingsKey,array());

        // ============================================
        //  format:  CHtml::link('label','url',array());
        /*
          $topNavArr['photo'] = array(
             'text'=>'相册',
             'url'=>array('/album/member'),
         );
        */
        // ==============================================
        foreach($menuConfig as $key=>$conf){
            $topNavArr[$key] = $conf ;
        }
        $settings->set($cateKey,$settingsKey,$topNavArr);
        return true ;
    }

    /**
     * @param string $pageId
     * @param $position
     * @param $fromModuleId
     * @param string $menuKey
     * @return bool
     */
    public static  function removeNav($pageId,$position, $fromModuleId,$menuKey=''){

        $settings = AppComponent::settings();
        $cateKey =  self::$settingKeyPrefix.':' .$pageId.':'.$position ;
        $settingsKey = $fromModuleId ;
        // 没有指定menuKey 表示删除全部的来自某个模块的菜单
        if(empty($menuKey)){
            // 有些方法无法判定返回的是什么 干脆用try/catch 包裹 不抛异常就是true 否则就是false
            // 模块或者方法间通信 “OK Pattern”(参考go语言的此模式)是个很好的处理方法
            // !!!! settings 的坑 先要load 再删！！！
            $settings->load($cateKey );

            $settings->delete($cateKey,$settingsKey);
            return true ;
        }else{
            $topNavArr  = $settings->get($cateKey,$settingsKey,array());
            // ============================================
            //  format:  CHtml::link('label','url',array());
            /*
              $topNavArr['photo'] = array(
                 'text'=>'相册',
                 'url'=>array('/album/member'),
             );
            */
            // ==============================================
            unset($topNavArr[$menuKey] );
            $settings->set($cateKey,$settingsKey,$topNavArr);
            return true ;
        }
    }

    /**
     * @param string $pageId
     * @param $position
     * @param string $fromModuleId
     * @param string $menuKey 暂时不支持该特性
     * @throws Exception
     * @return mixed
     * TODO  内部排序还没有实现 目前只能通过模块的安装先后 来觉定菜单的顺序！！
     */
    public static function getNav($pageId,$position, $fromModuleId='',$menuKey=''){

        $settings = AppComponent::settings();
        $cateKey =  self::$settingKeyPrefix.':' .$pageId.':'.$position ;
        $settingsKey = $fromModuleId ;

        $topNavArr  = $settings->get($cateKey,$settingsKey,array());
        if(empty($topNavArr)){
            $topNavArr = array() ;
        }
        if(!empty($menuKey)){
            throw new  Exception('not implemented!');

        }
        return $topNavArr ;
    }

    //=============================================================//
    //-----------------------------------------------------------------------\\

    /**
     * @param string $position
     * @param string $fromModuleId
     * @param array $menuConfig
     * @return bool
     */
    public static  function addUserSpaceNav($position, $fromModuleId,$menuConfig){
       return self::addNav('userSpace',$position,$fromModuleId,$menuConfig);

    }

    /**
     * @param $position
     * @param $fromModuleId
     * @param string $menuKey
     * @return bool
     */
    public static  function removeUserSpaceNav($position, $fromModuleId,$menuKey=''){
        return self::removeNav('userSpace',$position,$fromModuleId,$menuKey);
    }

    /**
     * @param $position
     * @param string $fromModuleId
     * @param string $menuKey 暂时不支持该特性
     * @throws Exception
     * @return mixed
     * TODO  内部排序还没有实现 目前只能通过模块的安装先后 来觉定菜单的顺序！！
     */
    public static function getUserSpaceNav($position, $fromModuleId='',$menuKey=''){
        return self::getNav('userSpace',$position,$fromModuleId,$menuKey);
     }
    //-----------------------------------------------------------------------//

    //-----------------------------------------------------------------------\\
    // #######################
    /**
     * 本段支持 用户中心的菜单安装卸载！
     */
    // #######################
    /**
     * @param string $position
     * @param string $fromModuleId
     * @param array $menuConfig
     * @return bool
     */
    public static  function addUserCenterNav($position, $fromModuleId,$menuConfig){
        return self::addNav('UserCenter',$position,$fromModuleId,$menuConfig);

    }

    /**
     * @param $position
     * @param $fromModuleId
     * @param string $menuKey
     * @return bool
     */
    public static  function removeUserCenterNav($position, $fromModuleId,$menuKey=''){
        return self::removeNav('UserCenter',$position,$fromModuleId,$menuKey);
    }

    /**
     * @param $position
     * @param string $fromModuleId
     * @param string $menuKey 暂时不支持该特性
     * @throws Exception
     * @return mixed
     * TODO  内部排序还没有实现 目前只能通过模块的安装先后 来觉定菜单的顺序！！
     */
    public static function getUserCenterNav($position, $fromModuleId='',$menuKey=''){
        return self::getNav('UserCenter',$position,$fromModuleId,$menuKey);
    }
    //-----------------------------------------------------------------------//
} 