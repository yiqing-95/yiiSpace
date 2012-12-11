<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-3
 * Time: 上午10:25
 * To change this template use File | Settings | File Templates.
 */
class YsVotingSystem
{


    /**
     * @static
     * @param string $sysName
     * @param int $id
     * 测试时要在post请求下进行 不然可能无法测试
     */
    public static function doRating($sysName, $id )
    {
        $systems = self::getAllSystems();

        if (!isset($systems[$sysName])) {
            return;
        }

        $sysObjVote = SysObjectVote::model()->populateRecord($systems[$sysName]);

        $sysObjVote->doRating($sysName,$id);
    }

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

    /**
     * @param string $objectName
     * @param string $tableRating
     * @param string $tableTrack
     * @param string $rowPrefix
     * @param integer $maxVotes
     * @param integer $duplicateSec
     * @param string $triggerTable
     * @param string $triggerFieldRate
     * @param string $triggerFieldRateCount
     * @param string $triggerFieldId
     * @param string $overrideClass
     * @return bool
     * 好像没有记录来自哪个module
     * 不过第一个参数 如果不用ar的类名的话可以考虑
     * 带点那种格式:  photo.album
     */
  static   public function registerSysObjectVoteConfig($objectName, $tableRating, $tableTrack, $rowPrefix, $maxVotes,
                                        $duplicateSec, $triggerTable, $triggerFieldRate,
                                        $triggerFieldRateCount, $triggerFieldId, $overrideClass = '')
    {
        $sysObjectVote = new SysObjectVote();
        $sysObjectVote->object_name = $objectName;
        $sysObjectVote->table_rating = $tableRating;
        $sysObjectVote->table_track = $tableTrack;
        $sysObjectVote->row_prefix = $rowPrefix;
        $sysObjectVote->max_votes = $maxVotes;
        $sysObjectVote->duplicate_sec = $duplicateSec;
        $sysObjectVote->trigger_table = $triggerTable;
        $sysObjectVote->trigger_field_rate = $triggerFieldRate;
        $sysObjectVote->trigger_field_rate_count = $triggerFieldRateCount;
        $sysObjectVote->trigger_field_id = $triggerFieldId;
       $rtn = $sysObjectVote->save();
       // WebUtil::printCharsetMeta();
       // echo __METHOD__, "|";print_r($sysObjectVote->getErrors());
          return $rtn;
    }



    /**
     * @static
     * @param string $objectName
     * @return int
     */
    public static function unRegisterObjectVotingConfig($objectName)
    {
      return SysObjectVote::model()->deleteAllByAttributes(array(
           'object_name'=>$objectName,
       ));
    }
}
