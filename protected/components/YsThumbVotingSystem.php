<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-3
 * Time: 上午10:25
 * To change this template use File | Settings | File Templates.
 */
class YsThumbVotingSystem
{


    /**
     * @static
     * @param string $objectName
     * @return array
     */
    public static function getObjectConfig($objectName){
        $allSystems = self::getAllSystems();
        if(isset($allSystems[$objectName])){
            return $allSystems[$objectName];
        }else{
            return array();
        }
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
            $cmd = $db->createCommand('SELECT * FROM `sys_object_thumb_vote`');
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
     * @param string $tableTrack
     * @param string $rowPrefix
     * @param integer $duplicateSec
     * @param string $triggerTable
     * @param string $triggerFieldUpVote
     * @param string $triggerFieldDownVote
     * @param string $triggerFieldId
     * @param integer $isOn
     * @return mixed
     */
   static  public  function registerConfig(  $objectName,  $tableTrack,    $triggerTable,  $triggerFieldUpVote='up_votes',  $triggerFieldDownVote = 'down_votes',  $triggerFieldId='id',  $isOn =1, $rowPrefix='',$duplicateSec=0 )
    {
        $sysObjectThumbVote = new SysObjectThumbVote();
        $sysObjectThumbVote->object_name = $objectName;
        $sysObjectThumbVote->table_track = $tableTrack;
        $sysObjectThumbVote->row_prefix = $rowPrefix;
        $sysObjectThumbVote->duplicate_sec = $duplicateSec;
        $sysObjectThumbVote->trigger_table = $triggerTable;
        $sysObjectThumbVote->trigger_field_up_vote = $triggerFieldUpVote;
        $sysObjectThumbVote->trigger_field_down_vote = $triggerFieldDownVote;
        $sysObjectThumbVote->trigger_field_id = $triggerFieldId;
        $sysObjectThumbVote->is_on = $isOn;
        $sysObjectThumbVote->save();
    }


    /**
     * @static
     * @param string $objectName
     * @return int
     */
    public static function unRegisterConfig($objectName)
    {
      return SysObjectThumbVote::model()->deleteAllByAttributes(array(
           'object_name'=>$objectName,
       ));
    }


    /**
     * @static
     * @param $tableName
     */
    public static function addUpDownCounterColumns($tableName){
       $tableSchema = Yii::app()->db->schema->getTable($tableName,true);
        $cmd = Yii::app()->db->createCommand();
        if($tableSchema->getColumn('up_votes') === null){
           $cmd->addColumn($tableName, 'up_votes', "int(11) NOT NULL DEFAULT '0'");
        }
        if($tableSchema->getColumn('down_votes') === null){
            $cmd->addColumn($tableName, 'down_votes', "int(11) NOT NULL DEFAULT '0'");
        }
    }

    /**
     * @static
     * @param $tableName
     */
    public static function dropUpDownCounterColumns($tableName){
        $tableSchema = Yii::app()->db->schema->getTable($tableName,true);
        $cmd = Yii::app()->db->createCommand();
        if($tableSchema->getColumn('up_votes')!==null){
         $cmd->dropColumn($tableName,'up_votes');
        }
        if($tableSchema->getColumn('down_votes')!==null){
            $cmd->dropColumn($tableName,'down_votes');
        }
    }

}
