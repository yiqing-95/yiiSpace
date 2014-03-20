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
 * 关于动作feed的聚合设计 ：
 * 拿上传照片来说 先根据某个条件（比如相册id）做查询 查到了就把当前动作合并到
 * 这个feed中  也就是 先查后插 如果不带聚合设计 就直接插入了 所以必要的字段上
 * 还是要带索引的limit1 的话也很快
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
     * @return string|false
     * serialized data for render the corresponding template
     * this data will be saved in database
     * ........................................
     * if return false means ignore this feed!
     * ........................................
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
