<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-16
 * Time: 下午3:01
 * To change this template use File | Settings | File Templates.
 * ..................................................................
 * work together with status_plugin
 * ..................................................................
 */
interface IStatusPlugin
{

    /**
     * @abstract
     * @return string
     */
    public function getFormPartial();

    /**
     * @abstract
     * @param Status $model will pass the default base Status obj
     * @param array|mixed $extraData
     * @return Status|null you can return a subClass of the Status class
     * ............................................
     * you can access the $_POST in this function
     * ............................................
     */
    public function processPost(Status $model,$extraData=array());


    /**
     * @abstract
     * @return string|false false means that you don't have the ClassTableInheritance table
     * ClassTableInheritance design pattern
     * http://martinfowler.com/eaaCatalog/classTableInheritance.html
     * ----------------------------------------------------
     * when query the status will left join this table
     * -----------------------------------------------------
     */
    public function getCtiTableName();

    /**
     * @abstract
     * @return array|string
     * array :array('image' ,'video_id')
     * string : 'image,video_id'
     * when left join it we will add a random table alias
     * --------------------------------------
     * you 'd better give it a column alias to avoid column alias collision
     *
     * --------------------------------------
     * will be used when query the status ,and it will be used in the display template of this plugin
     */
    public function getSelectColumns();


    /**
     * @abstract
     * @return string
     * the path alias of the tpl :
     * status.plugins.image.tpl.xx
     * @see http://www.yiiframework.com/doc/api/1.1/CController#getViewFile-detail
     */
    public function getViewTpl();
}
