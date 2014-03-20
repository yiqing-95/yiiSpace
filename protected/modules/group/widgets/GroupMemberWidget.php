<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-1-29
 * Time: 下午8:42
 */

class GroupMemberWidget  extends YsWidget{

    /**
     * @var int
     */
    public $max = 10 ;


    /**
     * @var int
     */
    public $groupId  ;

    public function run(){
        $criteria = new CDbCriteria();

        $criteria->limit = $this->max ;
        $criteria->addColumnCondition(array(
            'group_id'=>$this->groupId,
        ));
        // 加载用户模型
        $criteria->with = array('user');

        $dataProvider = new CActiveDataProvider('GroupMember',array(
           'criteria'=>$criteria,
        ));

        $this->render('groupMember/membersView',array(
            'dataProvider'=>$dataProvider,
        ));
    }
} 