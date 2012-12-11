<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-10
 * Time: 下午7:36
 * To change this template use File | Settings | File Templates.
 */
class YsCommentSystem
{



    /**
     * @static
     * @return array|mixed
     */
    public static function getAllSystems()
    {
        $cacheKey = __METHOD__;
        $systems = Yii::app()->cache->get($cacheKey);
        if ($systems === false) {
            $db = Yii::app()->db;
            $cmd = $db->createCommand('SELECT * FROM `sys_object_vote`');
            $dataReader = $cmd->query();
            // calling read() repeatedly until it returns false
            //  while(($row = $dataReader->read())!==false) { ... }
// using foreach to traverse through every row of data
            $systems = array();
            foreach ($dataReader as $row) {
                $systems[$row['object_name']] = $row;
            }
            $dependency = new CFileCacheDependency( /*Yii::getPathOfAlias()*/
                Yii::app()->getModulePath());
            // regenerate $value because it is not found in cache
            // and save it in cache for later use:
            Yii::app()->cache->set($cacheKey, $systems, 86400, $dependency);

        }
        return $systems;

    }


    /**
     * @return int
     * // it is called on cron every day or similar period
     */
    public function maintenance()
    {
        $time = time() - YS_OLD_VIEWS;
        $systems = self::getAllSystems();
        $iDeletedRecords = 0;
        $db = Yii::app()->db;
        $cmd = $db->createCommand();
        foreach ($systems as $system) {
            if (!$system['enable']) {
                continue;
            }

            $iDeletedRecords += $cmd->setText("DELETE FROM `{$system['table_track']}` WHERE `ts` < {$time}")->execute();
            $cmd->setText("OPTIMIZE TABLE `{$system['table_track']}`")->execute();
        }
        return $iDeletedRecords;
    }


    static   public function registerSysObjectVoteConfig()
    {

    }



    /**
     * @static
     * @param string $objectName
     * @return int
     */
    public static function unRegisterObjectCommentConfig($objectName)
    {
        return SysObjectCmt::model()->deleteAllByAttributes(array(
            'object_name'=>$objectName,
        ));
    }
}
