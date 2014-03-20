<?php
/**
 *  
 * User: yiqing
 * Date: 13-4-10
 * Time: 下午10:20
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class FriendLinksWidget extends CWidget{

    public function run(){
        $criteria = new CDbCriteria();
        $criteria->order = Yii::app()->db->quoteColumnName('order'). ' DESC';
        $friendLinks = SysFriendLink::model()->findAll($criteria);

        $this->render('friendLinks/friendLinks',array('links'=>$friendLinks));
    }
}