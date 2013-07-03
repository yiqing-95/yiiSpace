<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-19
 * Time: 下午2:43
 * To change this template use File | Settings | File Templates.
 * -----------------------------------------------------------------
 * @see http://codex.wordpress.org/Plugin_API
 */
class YsHookService
{

    /**
     * @static
     * @param string $hostModule
     * @param string $hookName
     * @return SysHook[]|array
     * -----------------------------------
     * 一定要加缓存 不然效率很成问题
     * -----------------------------------
     */
    static public function getHooks($hostModule = '', $hookName = ''){
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array(
            'host_module' => $hostModule,
            'hook_name' => $hookName,
        ));
        $criteria->order = 'priority DESC';
       return SysHook::model()->findAll($criteria);
    }
    /**
     * @static
     * @param string $hostModule
     * @param string $hookName
     * @param string $clientModule
     * @param string $clientHookName
     * @return bool
     */
    static public function hasHook($hostModule = '', $hookName = '', $clientModule = '', $clientHookName = '')
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array(
            'host_module' => $hostModule,
            'hook_name' => $hookName,
            'client_module' => $clientModule,
            'client_hook_name' => $clientHookName,
        ));

        return SysHook::model()->exists($criteria);
    }


    /**
     * @static
     * @param string $clientHookName
     * @return bool
     */
    static public function hasHookQueryByClientHookName($clientHookName = '')
    {
        return SysHook::model()->exists('client_hook_name=:client_hook_name', array(':client_hook_name' => $clientHookName));
    }

    /**
     * @static
     * @param string $clientHookName
     * @param array $updates
     * @return bool
     * 主要用来更新优先级
     */
    static public function updateHookByClientHookName($clientHookName = '',$updates = array()){
        $sysHook = SysHook::model()->findByAttributes(array(
            'client_hook_name' => $clientHookName,
        ));
        if($sysHook !== null){
          return  $sysHook->update($updates);
        }else{
            return false ;
        }
    }

    /**
     * @static
     * @param string $hostModule
     * @param string $hookName
     * @param string $clientModule
     * @param string $clientHookName
     * @param string $content
     * @param int $priority
     * @return bool
     */
    static public function addHook($hostModule = '', $hookName = '', $clientModule = '', $clientHookName = '', $content = '',$priority = 0)
    {
        $sysHook = new SysHook();
        $sysHook->host_module = $hostModule;
        $sysHook->hook_name = $hookName;
        $sysHook->client_module = $clientModule;
        $sysHook->client_hook_name = $clientHookName;
        $sysHook->hook_content = $content;
        $sysHook->priority = $priority ;
        $sysHook->create_time = time();
        return $sysHook->save();
    }



    /**
     * @static
     * @param string $hostModule
     * @param string $hookName
     * @param string $clientModule
     * @param string $clientHookName
     * @return bool
     */
    static public function removeHook($hostModule = '', $hookName = '', $clientModule = '', $clientHookName = '')
    {
        $sysHook = SysHook::model()->findByAttributes(array(
            'host_module' => $hostModule,
            'hook_name' => $hookName,
            'client_module' => $clientModule,
            'client_hook_name' => $clientHookName,
        ));
        if($sysHook !== null){
            return $sysHook->delete();
        }else{
            return false ;
        }
    }

    /**
     * @static
     * @param string $clientHookName
     * @return bool
     */
    static public function removeHookByClientHookName($clientHookName = '')
    {
        $sysHook = SysHook::model()->findByAttributes(array(
            'client_hook_name' => $clientHookName,
        ));
        if($sysHook !== null){
            return $sysHook->delete();
        }else{
            return false ;
        }
    }


    /**
     * @static
     * @param string $hostModule
     * @param string $hookName
     * @param string $clientModule
     * @return bool
     */
    static public function removeAllHook($hostModule , $hookName , $clientModule = '')
    {
       $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array(
            'host_module' => $hostModule,
            'hook_name' => $hookName,
        ));
        if(!empty($clientModule)){
            $criteria->addColumnCondition(array(
                'client_module' => $clientModule,
            ));
        }
        return SysHook::model()->deleteAll($criteria);
    }

    /**
     * @static
     * @param string $clientModule
     * @return bool
     */
    static public function removeAllHookByClientModule($clientModule = '')
    {
        return SysHook::model()->deleteAllByAttributes(array(
            'client_module' => $clientModule,
        ));
    }

    /**
     * @static
     * @throws CException
     */
    static public function applyHook()
    {
        throw new CException("not supported yet");
    }
}
