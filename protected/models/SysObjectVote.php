<?php
define( 'YS_PERIOD_PER_VOTE', 7 * 86400 );
define( 'YS_OLD_VOTES', 365*86400 ); // votes older than this number of seconds will be deleted automatically

Yii::import('application.models._base.BaseSysObjectVote');

/**
 *@note 测试时 要在 post 请求下才行
 */
class SysObjectVote extends BaseSysObjectVote
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function afterFind(){
        parent::afterFind();
        if(empty($this->duplicate_sec)){
            $this->duplicate_sec = YS_PERIOD_PER_VOTE;
        }
    }



    /**
     * @var int
     * AR model pk
     */
    public $objectId = 1;
    /**
     * @var int
     */
    public $voteCount = 0; // number of votes
    /**
     * @var float
     */
    public $voteRate = 0.0; // average rate

    public function doRating($objectName , $id )
    {
       // die($id . __METHOD__);

        if (!$this->isEnabled()) return;

        $this->objectId = $id;

        $voteResult = $this->_getVoteResult();
        //die(__METHOD__ . $voteResult);
       // $voteResult = 5 ;
        if ($voteResult) {
            if (!$this->makeVote($voteResult)) {
              echo CJSON::encode(array(
                 'status'=>'failure',
                  'msg'=>'已经投过了'
              ));
                exit;
            }
            $this->initVotes();
            echo CJSON::encode(array(
                'status'=>'success',
                'voteRate'=>$this->voteRate,
                'voteCount'=>$this->voteCount,
            ));
            //$this->voteRate . ',' . $this->voteCount;
            exit;
        }
    }

    protected function initVotes()
    {
        if (!$this->isEnabled()) return;

        $a = $this->getVote($this->objectId);
        if (!$a) return;
        $this->voteCount = $a['count'];
        $this->voteRate = $a['rate'];
    }

    function makeVote($iVote)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        if ($this->isDuplicateVote()) {
            return false;
        }
        // if (!$this->checkAction()) return false;

        //  can not make voting to self
        if ($this->object_name == 'profile' && $this->objectId == Yii::app()->user->getId()) {
            return false;
        }

        if ($this->putVote($this->objectId,WebUtil::getIp(), $iVote)) {
            $this->_triggerVote();
            /*
            $oZ = new BxDolAlerts($this->_sSystem, 'rate', $this->getId(), $_COOKIE['memberID'], array ('rate' => $iVote));
            $oZ->alert();
            */
            return true;
        }
        return false;
    }


    function isDuplicateVote()
    {
        if (!$this->isEnabled()) return false;
        $sPre = $this->row_prefix;
        $sTable = $this->table_track;
        $iSec = $this->duplicate_sec;

        $ip = WebUtil::getIp();
        /**
         * the  query result can be zero !!
         */
        return $this->dbConnection->createCommand("SELECT `{$sPre}id`
        FROM {$sTable}
        WHERE `{$sPre}ip` = '{$ip}'
        AND `{$sPre}id` = '{$this->objectId}'
         AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`{$sPre}date`) < $iSec")->queryScalar() !== false;
    }


    public function isEnabled()
    {
        return $this->is_on;
    }

    public function getMaxVote()
    {
        return $this->max_votes;
    }

    public function getVoteCount()
    {
        return $this->voteCount;
    }

    public function getVoteRate()
    {
        return $this->voteRate;
    }

    public function getSystemName()
    {
        return $this->object_name;
    }

    /**
     * set id to operate with votes
     */
    public function setId($iId)
    {
        if ($iId == $this->objectId) return;
        $this->initVotes();
    }

    public function getSqlParts($sMailTable, $sMailField)
    {
        if ($this->isEnabled()) {
            if ($sMailTable)
                $sMailTable .= '.';

            if ($sMailField)
                $sMailField = $sMailTable . $sMailField;

            $sPre = $this->row_prefix;
            $sTable = $this->table_rating;

            return array(
                'fields' => ",$sTable.`{$sPre}rating_count` as `voting_count`, ($sTable.`{$sPre}rating_sum` / $sTable.`{$sPre}rating_count`) AS `voting_rate` ",
                //'fields' => ",34 as `voting_count`, 2.5 AS `voting_rate` ",
                'join' => " LEFT JOIN $sTable ON ({$sTable}.`{$sPre}id` = $sMailField) "
            );
        } else {
            return array();
        }
    }


    public function deleteVotings($iId)
    {
        if (!(int)$iId) {
            return false;
        }
        $sPre = $this->row_prefix;
        $sTable = $this->table_track;
        $this->dbConnection->createCommand("DELETE FROM {$sTable} WHERE `{$sPre}id` = '$iId'")->execute();
        return $this->dbConnection->createCommand("DELETE FROM {$this->table_rating} WHERE `{$sPre}id` = '$iId'");
    }

    public  function getTopVotedItem($iDays, $sJoinTable = '', $sJoinField = '', $sWhere = '')
    {
        $sPre = $this->_aSystem['row_prefix'];
        $sTable = $this->_aSystem['table_track'];

        $sJoin = $sJoinTable && $sJoinField ? " INNER JOIN $sJoinTable ON ({$sJoinTable}.{$sJoinField} = $sTable.`{$sPre}id`) " : '';

        return $this->getOne("SELECT
        $sTable.`{$sPre}id`, COUNT($sTable.`{$sPre}id`) AS `voting_count`
        FROM {$sTable} $sJoin
        WHERE TO_DAYS(NOW()) - TO_DAYS($sTable.`{$sPre}date`) <= $iDays $sWhere
        GROUP BY $sTable.`{$sPre}id`
        HAVING `voting_count` > 2
        ORDER BY `voting_count` DESC LIMIT 1");
    }


    public function getVotedItems($sIp)
    {
        $sPre = $this->row_prefix;
        $sTable = $this->table_track;
        $iSec =  $this->duplicate_sec ; //$this->_aSystem['is_duplicate'];
        return $this->getAll("SELECT `{$sPre}id`
        FROM {$sTable}
        WHERE
        `{$sPre}ip` = '$sIp' AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`{$sPre}date`) < $iSec
         ORDER BY `{$sPre}date` DESC"
        );
    }

    /**
     * it is called on cron every day or similar period to clean old votes
     */
    public function maintenance()
    {
        $iDeletedRecords = 0;
        foreach ($this->_aSystems as $aSystem) {
            if (!$aSystem['is_on'])
                continue;
            $sPre = $aSystem['row_prefix'];
            $iDeletedRecords += $GLOBALS['MySQL']->query("DELETE FROM `{$aSystem['table_track']}` WHERE `{$sPre}date` < DATE_SUB(NOW(), INTERVAL " . YS_OLD_VOTES . " SECOND)");
            $GLOBALS['MySQL']->query("OPTIMIZE TABLE `{$aSystem['table_track']}`");
        }
        return $iDeletedRecords;
    }

    /** private functions
     *********************************************/

    protected function _getVoteResult()
    {

        $request = request();
        if (($vote = $request->getParam($this->post_name, false))===false) {
            return 0;
        }
        //   die(__METHOD__.'yy');
        $vote = (int)$vote;
        if (!$vote) {
            return 0;
        }
        if ($vote > $this->getMaxVote()) {
            $vote = $this->getMaxVote();
        }
        if ($vote < 1) {
            $vote = 1;
        }
        return $vote;
    }

    protected  function _triggerVote()
    {
        if (!$this->trigger_table)
            return false;
        $iId = $this->objectId;
        if (!is_numeric($iId))
            return false;

        $this->initVotes();
        $iCount = $this->getVoteCount();
        $fRate = $this->getVoteRate();

        return $this->updateTriggerTable($iId, $fRate, $iCount);
    }

    //======================================================================================

    /**
     * @param $objectId
     * @return mixed
     */
    protected function  getVote($objectId)
    {
        $pre = $this->row_prefix;
        $tableRating = $this->table_rating;
        $dbCmd = $this->dbConnection->createCommand("SELECT
        `{$pre}rating_count` as `count`, (`{$pre}rating_sum` / `{$pre}rating_count`) AS `rate`
        FROM {$tableRating} WHERE `{$pre}id` = '$objectId' ");
        return $dbCmd->queryRow();
    }

    public function  putVote($objectId = 0, $ip = '', $rate = 3)
    {
        $rowPrefix = $this->row_prefix;

        $tableRating = $this->table_rating;
        $db = $this->dbConnection;
        $dbCmd = $db->createCommand("SELECT `{$rowPrefix}id` FROM {$tableRating} WHERE `{$rowPrefix}id` = :objectId ");
        $rtn = $dbCmd->queryScalar(array(':objectId' => $objectId));

        // if  exist then update else do inserting
        if ($rtn !==false) {
            $dbCmd = $this->dbConnection->createCommand("UPDATE {$tableRating}
            SET `{$rowPrefix}rating_count` = `{$rowPrefix}rating_count` + 1, `{$rowPrefix}rating_sum` = `{$rowPrefix}rating_sum` + '{$rate}'
            WHERE `{$rowPrefix}id` = '{$objectId}'");
            $ret = $dbCmd->execute();
        } else {
            $dbCmd = $this->dbConnection->createCommand("INSERT INTO {$tableRating}
            SET `{$rowPrefix}id` = '{$objectId}', `{$rowPrefix}rating_count` = '1', `{$rowPrefix}rating_sum` = '{$rate}'");
            $ret = $dbCmd->execute();

        }
        if (!$ret) return $ret;

        $tableTrack = $this->table_track;
        return $this->dbConnection->createCommand("INSERT INTO {$tableTrack}
       SET `{$rowPrefix}id` = '$objectId', `{$rowPrefix}ip` = '{$ip}', `{$rowPrefix}date` = NOW()")
            ->execute() ;
    }

    /**
     * @param int $iId
     * @param float $fRate
     * @param int $iCount
     * @return int
     */
    public function updateTriggerTable($iId, $fRate, $iCount)
    {
        /*
        return $this->dbConnection->createCommand("UPDATE `{$this->trigger_table}`
         SET `{$this->trigger_field_rate}` = '$fRate',
         `{$this->trigger_field_rate_count}` = '$iCount'
         WHERE `{$this->trigger_field_id}` = '$iId'")->execute();*/
        $cmd = $this->dbConnection->createCommand("UPDATE `{$this->trigger_table}`
         SET `{$this->trigger_field_rate}` = :rate,
         `{$this->trigger_field_rate_count}` = :count
         WHERE `{$this->trigger_field_id}` = :id");
        //echo "<<" ,$iId ,">>";
        //echo(__METHOD__. $cmd->getText());
        return $cmd->execute(array(
            ':rate'=>$fRate,
            ':count'=>$iCount,
            ':id'=>$iId,
        ));
    }
}