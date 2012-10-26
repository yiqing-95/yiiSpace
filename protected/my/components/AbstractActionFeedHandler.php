<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-25
 * Time: 下午5:46
 * To change this template use File | Settings | File Templates.
 * .........................................................................
 *@see  http://activitystrea.ms/specs/json/1.0/
 * http://activitystrea.ms/specs/json/schema/activity-schema.html
 * http://stackoverflow.com/questions/1443960/how-to-implement-the-activity-stream-in-a-social-network
 * .........................................................................
 */
 abstract class AbstractActionFeedHandler implements IActionFeedHandler
{

     /**
      * @var int
      */
     protected $actionType = 1;

     /**
      * @var CActiveRecord|string
      */
     protected $subject ;

     /**
      * @param int $actionType
      *   ar_insert <==>1  |ar_update <==> 2 |
      * @return mixed
      */
     public function setActionType($actionType = 1)
     {
         $this->actionType = $actionType;
     }

     /**
      * @param CActiveRecord|string $subject
      * the current activeRecord
      * @return mixed
      */
     public function setSubject($subject)
     {
         $this->subject = $subject;
     }

     /**
      * @return string serialized data for render the corresponding template
      */
     public function getData()
     {
         return ($this->subject instanceof CActiveRecord)? serialize($this->subject->attributes):'';
     }

     /**
      * @return int|mixed
      */
     public function getObjectId(){
        if($this->subject instanceof CActiveRecord){
            //don't support the compose key
            return $this->subject->getPrimaryKey();
        }else{
            return 0;
        }
     }

     /**
      * @return string
      */
     public function getTargetType(){
         return '';
     }

     /**
      * @return int
      */
     public function getTargetId(){
         return 0;
     }

     /**
      * @return int
      */
     public function getTargetOwner(){
         return 0;
     }

     /**
      * @param $data
      * @return mixed

     public function renderTitle($data)
     {

     }
      */

     /**
      * @param $data
      * @return mixed
      */
     public function renderBody($data)
     {
        echo '';
     }
 }
