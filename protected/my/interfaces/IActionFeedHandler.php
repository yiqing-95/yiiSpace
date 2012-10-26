<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-25
 * Time: 下午3:38
 * To change this template use File | Settings | File Templates.
 * ----------------------------------------------------------------
 * @see http://stackoverflow.com/questions/1443960/how-to-implement-the-activity-stream-in-a-social-network/1766371#1766371
 * @link  http://activitystrea.ms/
 * -----------------------------------------------------------------
 */
interface IActionFeedHandler
{

    /**
     * @abstract
     * @param int $actionType
     *   ar_insert <==>1  |ar_update <==> 2 |
     * @return mixed
     */
    public function setActionType($actionType=1);

    /**
     * @abstract
     * @param CActiveRecord|string $subject
     * the current activeRecord
     * @return mixed
     */
    public function setSubject( $subject);

    /**
     * @abstract
     * @return string serialized data for render the corresponding template
     * this data will be saved in database
     */
    public function getData();



    /**
     * @return int|mixed
     */
    public function getObjectId();

    /**
     * @return string
     */
    public function getTargetType();

    /**
     * @return int
     */
    public function getTargetId();

    /**
     * @return int
     */
    public function getTargetOwner();

    /**
     * @abstract
     * @param $data
     * @return mixed
     */
    public function renderTitle($data);

    /**
     * @abstract
     * @param $data
     * @return mixed
     */
    public function renderBody($data);

}
