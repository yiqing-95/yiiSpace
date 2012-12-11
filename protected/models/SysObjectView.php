<?php

Yii::import('application.models._base.BaseSysObjectView');

class SysObjectView extends BaseSysObjectView
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * @var int
     * AR model pk
     */
    public $objectId = 1;

    /**
     * @param int $objectId
     * @return bool
     */
    public function doView($objectId = 0)
    {
        if (!$this->enable) {
            return;
        }
        $this->objectId = $objectId;

        $memberId = user()->getIsGuest() ? 0 : user()->getId();
        $ip =  WebUtil::getIp(); //$_SERVER['REMOTE_ADDR']; die($ip . inet_pton($ip));

        if($ip == 'UNKNOWN'){
            $ip = '127.0.0.1';
        }
       // die($ip . WebUtil::inet_aton($ip));
        $time = time();

        $criteria = new CDbCriteria();
        //$criteria = array();
        if ($memberId) {
            $criteria->addColumnCondition(array(
                'id' => $this->objectId,
                'viewer' => $memberId
            ));
            $condition = $criteria->condition;
            $params = $criteria->params;
        } else {
            /**
             * For IPv4 and IPv6 support use inet_pton() and inet_ntop(), these are availiable since PHP 5.1+ and mimic exactly the equivalent MySQL functions.
             * Otherwise just use ip2long() and long2ip().
             */
            $criteria->addColumnCondition(array(
                'id' => $this->objectId,
                'viewer' => 0,
                'ip' => WebUtil::inet_aton($ip),
            ));
            $condition = $criteria->condition;
            $params = $criteria->params;
        }

        $easyQuery = EasyQuery::instance($this->table_track);
       (int)$ts = $easyQuery->queryScalar('ts', $condition, $params);


        if ($ts === false) {

            $return = $easyQuery->insert(array(
                'id' => $this->objectId,
                'viewer' => $memberId,
                'ip' => WebUtil::inet_aton($ip),
                'ts' => $time,
            ));
        //    print_r($ts); die(__METHOD__);
        } elseif (($time - $ts) > $this->period) {
            $return = $easyQuery->update(array(
                    'ts' => $time,
                ), 'id=:id AND viewer=:viewer AND ip=:ip', array(
                    ':id' => $this->objectId,
                    ':viewer' => $memberId,
                    ':ip' => WebUtil::inet_aton($ip),
                )
            );
        }else{
            $return = true ;
        }
        if ($return) {
            $this->triggerView();
            $currentModule = YiiUtil::getCurrentModule();
             $currentModuleId = $currentModule == null? '': $currentModule->getId();
            // SysEventManager::fireEvent(new GEvent($memberId,'view',$this->name,array(),$currentModuleId ));
            return true;
        }
        return false;
    }

    protected  function  triggerView()
    {
        $easyQuery = EasyQuery::instance($this->trigger_table);
        $easyQuery->update(array(
           $this->trigger_field_views =>new CDbExpression("{$this->trigger_field_views} + 1"),
        ),"{$this->trigger_field_id} = :triggerFieldId",array(':triggerFieldId'=>$this->objectId));
    }

    // call this function when associated object is deleted
  public   function onObjectDelete ($iId = 0)
    {
        $iId = (int) $iId;
        if( $GLOBALS['MySQL']->query("DELETE FROM `{$this->_aSystem['table_track']}`
            WHERE `id` = '" . ($iId ? $iId : $this->getId()) . "'") ) {
            $GLOBALS['MySQL']->query("OPTIMIZE TABLE `{$this->_aSystem['table_track']}`");
        }
    }

}